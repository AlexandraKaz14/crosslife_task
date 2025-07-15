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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 10, 2)->default(0);
        });
        DB::statement("ALTER TABLE users ADD CONSTRAINT chk_balance_non_negative CHECK (balance >= 0)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users DROP CHECK chk_balance_non_negative");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};
