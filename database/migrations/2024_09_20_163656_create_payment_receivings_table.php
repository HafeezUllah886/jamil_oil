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
        Schema::create('payment_receivings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_id')->constrained('accounts', 'id');
            $table->foreignId('account_id')->constrained('accounts', 'id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->date('date');
            $table->float('amount');
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
        Schema::dropIfExists('payment_receivings');
    }
};
