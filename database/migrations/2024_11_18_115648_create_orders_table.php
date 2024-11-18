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
        // Create orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_member');
            $table->string('invoice')->unique();
            $table->integer('grand_total');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_member')->references('id')->on('members')->onDelete('cascade');
        });

        // Create order_details table
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_produk');
            $table->integer('jumlah');
            $table->integer('total');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_order')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('id_produk')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
    }
};
