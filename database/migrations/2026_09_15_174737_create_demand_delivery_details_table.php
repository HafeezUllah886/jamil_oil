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
        Schema::create('demand_delivery_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained('demand_deliveries', 'id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->float('qty');
            $table->float('price');
            $table->float('amount');
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demand_delivery_details');
    }
};
