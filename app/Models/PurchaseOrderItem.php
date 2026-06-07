<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (PurchaseOrderItem $item): void {
            $item->attributes['subtotal'] = round(
                ((float) $item->quantity) * ((float) $item->unit_price),
                2
            );
        });

        static::saved(function (PurchaseOrderItem $item): void {
            $item->purchaseOrder?->recalculateTotalAmount();
        });

        static::deleted(function (PurchaseOrderItem $item): void {
            $item->purchaseOrder?->recalculateTotalAmount();
        });
    }
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
