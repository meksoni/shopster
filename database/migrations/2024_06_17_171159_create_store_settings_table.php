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
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('cp_name')->nullable();
            $table->string('cp_logo')->default(NULL);
            $table->string('cp_email')->nullable();
            $table->string('cp_phone')->nullable();
            $table->string('global_currency')->nullable();
            $table->string('cp_pib')->nullable();
            $table->string('cp_mb')->nullable();

            $table->string('cp_address')->nullable();
            $table->string('cp_city')->nullable();
            $table->string('cp_country')->nullable();
            $table->string('cp_zip')->nullable();

            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
