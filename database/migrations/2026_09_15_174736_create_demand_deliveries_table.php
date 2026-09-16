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
        Schema::create('demand_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demand_id')->constrained('demands', 'id')->onDelete('cascade');
            $table->date('date');
            $table->string('vehicle_no')->nullable();
            $table->string('driver_name')->nullable();
            $table->float('delivery_charges')->default(0);
            $table->float('total_amount')->default(0);
            $table->text('notes')->nullable();
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demand_deliveries');
    }
};
