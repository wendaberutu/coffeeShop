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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique()->nullable();
            $table->integer('umur')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->bigInteger('no_whatshap'); // Use bigInteger for phone numbers
            $table->string('role')->default('user');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**p
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
