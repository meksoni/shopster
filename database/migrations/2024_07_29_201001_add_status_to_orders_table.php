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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'na_cekanju', // Na cekanju
                'poslato', // Poslato
                'isporuceno', // Isporuceno
                'otkazano', // Otkazano
                'vraceno', // Vraceno
                'neuspesno' // Neuspesno
            ])->default('na_cekanju');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
