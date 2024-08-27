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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->tinyText('short_description')->nullable();
            $table->enum('vat_rate', ['0', '10', '20'])->default('20');
            $table->decimal('price', 10, 2);

            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->date('discount_start_date')->nullable();
            $table->date('discount_end_date')->nullable();

            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sub_sub_category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('unit_measure',['kom', 'm'])->default('kom');
            $table->string('sku');
            $table->string('barcode')->nullable();
            $table->enum('track_quantity',['Yes', 'No'])->default('Yes');
            // Istaknut Proizvod
            $table->enum('is_featured', ['Yes', 'No'])->default('No');
            $table->integer('quantity')->nullable();
            // Status proizvoda / Aktivan\Neaktivan / Default - Aktivan
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
