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
        Schema::create('dailysheets', function (Blueprint $table) {
            $table->id();
            $table->timestamp('from');
            $table->timestamp('to');
            $table->timestamps();
        });

        Schema::create('dailysheets_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dailysheet_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('openingMeter', 10, 0)->default(0);
            $table->decimal('closingMeter', 10, 0)->default(0);
            $table->decimal('totalLtr', 10, 2)->default(0);
            $table->decimal('checkingLtr', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailysheets');
    }
};
