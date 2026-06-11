<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PurchaseReturnItem extends Model
{
    protected $fillable = [
        'purchase_return_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted(): void
{
    static::saving(function (PurchaseReturnItem $item) {

        $item->subtotal =
            ((float) $item->quantity)
            *
            ((float) $item->unit_price);
    });
}
}
