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
        Schema::create('pemeriksaan_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('petugas_id')->nullable();
            $table->boolean('oli')->default(false);
            $table->boolean('tekanan_ban')->default(false);
            $table->boolean('tool_set')->default(false);
            $table->boolean('mesin')->default(false);
            $table->text('catatan')->nullable();
            $table->enum('status', ['menunggu', 'lulus', 'tidak_lulus'])->default('menunggu');
            $table->timestamp('tanggal_pemeriksaan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('transaksi_id')->references('id_transaksi')->on('transaksi');
            $table->foreign('petugas_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_kendaraans');
    }
};
