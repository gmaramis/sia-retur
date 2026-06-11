<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\GoodsReceiptItem;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'unit',
        'minimum_stock',
        'shelf_life_days',
        'default_purchase_price',
        'stock_ready',
        'stock_hold'
    ];

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function goodsReceiptItems()
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function receivingIssues()
    {
        return $this->hasMany(ReceivingIssue::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function recalculateStock(): void
    {
        $ready = GoodsReceiptItem::where('product_id', $this->id)
            ->sum('good_quantity');

        $hold = GoodsReceiptItem::where('product_id', $this->id)
            ->sum('hold_quantity');

        $this->update([
            'stock_ready' => $ready,
            'stock_hold' => $hold,
        ]);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
