<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Refactor status pesanan: accepted→dalam_antrian, perlu_dibayar→terkirim, terbayar→terkirim
     * Standardize payment_status: unpaid→belum_dibayar, paid→telah_dibayar
     */
    public function up(): void
    {
        // Step 1: Update payment_status values to new naming convention
        DB::table('pesanans')->where('payment_status', 'unpaid')->update(['payment_status' => 'belum_dibayar']);
        DB::table('pesanans')->where('payment_status', 'paid')->update(['payment_status' => 'telah_dibayar']);

        // Step 2: Migrate old status values to new ones
        // 'accepted' → 'dalam_antrian' (admin confirmed, in queue for worker)
        DB::table('pesanans')->where('status', 'accepted')->update([
            'status' => 'dalam_antrian',
            'payment_status' => 'belum_dibayar',
        ]);

        // 'perlu_dibayar' → 'terkirim' + payment_status = 'belum_dibayar'
        DB::table('pesanans')->where('status', 'perlu_dibayar')->update([
            'status' => 'terkirim',
            'payment_status' => 'belum_dibayar',
        ]);

        // 'terbayar' → 'terkirim' + payment_status = 'telah_dibayar'
        DB::table('pesanans')->where('status', 'terbayar')->update([
            'status' => 'terkirim',
            'payment_status' => 'telah_dibayar',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: 'dalam_antrian' → 'accepted'
        DB::table('pesanans')->where('status', 'dalam_antrian')->update(['status' => 'accepted']);

        // Reverse: 'diproses' → 'accepted' (closest mapping)
        DB::table('pesanans')->where('status', 'diproses')->update(['status' => 'accepted']);

        // Reverse: 'terkirim' + telah_dibayar → 'terbayar'
        DB::table('pesanans')
            ->where('status', 'terkirim')
            ->where('payment_status', 'telah_dibayar')
            ->update(['status' => 'terbayar']);

        // Reverse: 'terkirim' + belum_dibayar → 'perlu_dibayar'
        DB::table('pesanans')
            ->where('status', 'terkirim')
            ->where('payment_status', 'belum_dibayar')
            ->update(['status' => 'perlu_dibayar']);

        // Reverse payment_status naming
        DB::table('pesanans')->where('payment_status', 'belum_dibayar')->update(['payment_status' => 'unpaid']);
        DB::table('pesanans')->where('payment_status', 'telah_dibayar')->update(['payment_status' => 'paid']);
    }
};
