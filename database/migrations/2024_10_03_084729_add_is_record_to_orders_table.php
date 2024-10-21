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
        Schema::table('orders', function (Blueprint $table) {
            Schema::table('orders', function (Blueprint $table) {
                // Menambahkan kolom 'is_record' setelah kolom 'payment_amount'
                $table->boolean('is_record')->default(false)->after('payment_amount');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kolom 'is_record' jika rollback dilakukan
            $table->dropColumn('is_record');
        });
    }
};
