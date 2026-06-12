<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;


class PurchaseReturn extends Model
{
    protected $fillable = [
        'return_number',
        'supplier_id',
        'receiving_issue_id',
        'return_date',
        'status',
        'notes',
    ];
    
    
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function receivingIssue(): BelongsTo
    {
        return $this->belongsTo(ReceivingIssue::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    protected static function booted(): void
{
    static::updated(function (PurchaseReturn $return) {

        if (
            $return->getOriginal('status') !== 'completed'
            && $return->status === 'completed'
        ) {

            foreach ($return->items as $item) {

                $product = $item->product;

                if (! $product) {
                    continue;
                }

                $product->decrement(
                    'stock_hold',
                    (int) $item->quantity
                );
            }
        }
    });
}
}
