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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderable_id');
            $table->string('orderable_type'); // Može biti 'UserOrder' ili 'CompanyOrder'
            $table->double('subtotal', 10, 2);
            $table->double('shipping', 10, 2);
            $table->double('grand_total', 10, 2);
            $table->enum('payment_method', ['cash_on_delivery', 'bank_transfer']);
            $table->enum('delivery_method', ['address', 'store']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
