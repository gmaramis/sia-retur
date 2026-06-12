<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StockMovement;

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
        static::updated(function (PurchaseReturn $return): void {

            if (
                $return->getOriginal('status') !== 'completed'
                && $return->status === 'completed'
            ) {

                foreach ($return->items as $item) {

                    $product = $item->product;

                    if (! $product) {
                        continue;
                    }

                    // Kurangi Stock Hold
                    $product->decrement(
                        'stock_hold',
                        (int) $item->quantity
                    );

                    // Catat ke Stock Movement
                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'movement_date' => now(),
                        'movement_type' => 'OUT_HOLD',
                        'location' => 'Gudang Utama',
                        'quantity' => (int) $item->quantity,
                        'reference_type' => 'Purchase Return',
                        'reference_id' => $return->id,
                        'description' => 'Barang diretur ke supplier',
                    ]);
                }
            }
        });
    }
}