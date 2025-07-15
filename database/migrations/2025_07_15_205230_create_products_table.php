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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->index();
            $table->integer('stock');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE products ADD CONSTRAINT chk_price_non_negative CHECK (price >= 0)");
        DB::statement("ALTER TABLE products ADD CONSTRAINT chk_stock_non_negative CHECK (stock >= 0)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE products DROP CHECK chk_price_non_negative");
        DB::statement("ALTER TABLE products DROP CHECK chk_stock_non_negative");
        Schema::dropIfExists('products');
    }
};
