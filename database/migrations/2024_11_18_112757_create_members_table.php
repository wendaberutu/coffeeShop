<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('nama_member');
            $table->string('alamat');
            $table->string('email');
            $table->string('no_whatshap')->unique();
            $table->integer('umur'); // Kolom umur wajib diisi
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_lahir');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
