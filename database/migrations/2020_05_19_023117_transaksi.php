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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('mobil_id');
            $table->string('nama_penyewa');
            $table->dateTime('tanggal_mulai_sewa');
            $table->dateTime('tanggal_akhir_sewa');
            $table->dateTime('tanggal_kembali')->nullable();
            $table->decimal('tambahan_sewa', 10, 2)->nullable();
            $table->text('kerusakan')->nullable();
            $table->decimal('total_biaya', 10, 2);
            $table->decimal('denda', 10, 2)->nullable();
            $table->enum('status_peminjaman', ['pengajuan', 'disetujui', 'ditolak', 'berjalan', 'selesai', 'dibatalkan'])
                ->default('pengajuan');
            $table->unsignedBigInteger('approval_by')->nullable();
            $table->timestamp('approval_at')->nullable();
            $table->text('keterangan_pengajuan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->boolean('status_mobil_keluar')->default(false);
            $table->timestamp('tanggal_mobil_keluar')->nullable();
            $table->boolean('status_mobil_masuk')->default(false);
            $table->timestamp('tanggal_mobil_masuk')->nullable();
            $table->string('verifikasi_wajah_masuk_status')->nullable();
            $table->timestamp('verifikasi_wajah_masuk_timestamp')->nullable();
            $table->string('verifikasi_wajah_masuk_foto')->nullable();
            $table->float('verifikasi_wajah_masuk_confidence')->nullable();
            $table->string('verifikasi_wajah_keluar_status')->nullable();
            $table->timestamp('verifikasi_wajah_keluar_timestamp')->nullable();
            $table->string('verifikasi_wajah_keluar_foto')->nullable();
            $table->float('verifikasi_wajah_keluar_confidence')->nullable();
            $table->text('verifikasi_wajah_keterangan')->nullable();

            // Foreign keys
            $table->foreign('mobil_id')->references('mobil_id')->on('mobil');
            $table->foreign('approval_by')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
