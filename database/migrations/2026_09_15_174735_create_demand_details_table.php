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
        Schema::create('demand_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demand_id')->constrained('demands', 'id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->float('qty');
            $table->float('price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demand_details');
    }
};
