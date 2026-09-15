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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('accounts', 'id');
            $table->foreignId('attendant_id')->constrained('attendants', 'id');
            $table->date('date');
            $table->float('total')->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['paid', 'pending'])->default('paid');
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
