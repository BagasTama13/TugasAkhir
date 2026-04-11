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
        Schema::table('users', function (Blueprint $table) {
            $table->string('alamat')->nullable()->after('email');
            $table->string('no_hp')->nullable()->after('alamat');
        });

        Schema::table('pesanans', function (Blueprint $table) {
            $table->foreignId('produk_id')->nullable()->after('user_id')->constrained('produks')->onDelete('set null');
            $table->string('catatan')->nullable()->after('description');
            $table->string('no_whatsapp')->nullable()->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'no_hp']);
        });

        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->dropColumn(['produk_id', 'catatan', 'no_whatsapp']);
        });
    }
};
