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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('team_id')->constrained('teams');
            $table->foreignId('user_id')->constrained(table: 'users');
            $table->enum('type', ['in', 'out', 'start'])->default('');
            $table->integer('quantity');
            $table->unsignedBigInteger('unit_cost')->nullable();
            $table->unsignedBigInteger('total');
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('remaining_quantity')->default(0); // Menambah kolom sisa quantity
            $table->boolean('is_active')->default(true); // Status batch masih aktif/tidak
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
