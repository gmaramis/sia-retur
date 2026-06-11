<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceiptItem extends Model
{
    protected $fillable = [
        'goods_receipt_id',
        'product_id',
        'ordered_quantity',
        'received_quantity',
        'good_quantity',
        'hold_quantity',
        'unit_price',
        'subtotal',
        'expiry_date',
        'condition_status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (GoodsReceiptItem $item): void {
            $item->attributes['subtotal'] = round(
                ((float) $item->received_quantity) * ((float) $item->unit_price),
                2
            );
        });

        static::saved(function (GoodsReceiptItem $item): void {

            $product = $item->product;

            if (! $product) {
                return;
            }

            $product->increment(
                'stock_ready',
                (int) $item->good_quantity
            );

            $product->increment(
                'stock_hold',
                (int) $item->hold_quantity
            );
        });
    }

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
