<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('goods_receipt_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('goods_receipt_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('ordered_quantity')->default(0);

            $table->integer('received_quantity')->default(0);

            $table->integer('good_quantity')->default(0);

            $table->integer('hold_quantity')->default(0);

            $table->decimal('unit_price', 15, 2)->default(0);

            $table->decimal('subtotal', 15, 2)->default(0);

            $table->date('expiry_date')->nullable();

            $table->string('condition_status')->default('good');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_items');
    }
};
