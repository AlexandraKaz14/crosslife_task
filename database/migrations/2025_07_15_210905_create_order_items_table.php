<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('amount');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
        DB::statement("ALTER TABLE order_items ADD CONSTRAINT chk_order_item_amount CHECK (amount > 0)");
        DB::statement("ALTER TABLE order_items ADD CONSTRAINT chk_order_item_price CHECK (price >= 0)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE order_items DROP CHECK chk_order_item_amount ");
        DB::statement("ALTER TABLE order_items DROP CHECK chk_order_item_price ");
        Schema::dropIfExists('order_items');
    }
};
