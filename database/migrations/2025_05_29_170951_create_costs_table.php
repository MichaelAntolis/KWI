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
        Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // siapa yang input
            $table->string('item_name'); // nama barang/bahan
            $table->decimal('quantity', 10, 2)->default(1); // jumlah barang
            $table->string('unit')->default('pcs'); // satuan (pcs, kg, liter, dll)
            $table->integer('unit_price'); // harga per unit
            $table->integer('total_price'); // total harga
            $table->text('description')->nullable(); // keterangan tambahan
            $table->date('purchased_date'); // tanggal pembelian
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costs');
    }
};