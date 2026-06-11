<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
