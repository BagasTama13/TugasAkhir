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
        if (!Schema::hasColumn('pemasukans', 'pesanan_id')) {
            Schema::table('pemasukans', function (Blueprint $table) {
                $table->foreignId('pesanan_id')->nullable()->constrained('pesanans')->nullOnDelete()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pemasukans', 'pesanan_id')) {
            Schema::table('pemasukans', function (Blueprint $table) {
                $table->dropForeign(['pesanan_id']);
                $table->dropColumn('pesanan_id');
            });
        }
    }
};
