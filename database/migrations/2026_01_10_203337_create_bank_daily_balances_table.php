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
        Schema::create('bank_daily_balances', function (Blueprint $table) {
    $table->id();
    $table->foreignId('bank_id')->constrained()->cascadeOnDelete();
    $table->date('balance_date');
    $table->decimal('available_balance', 15, 2);
    $table->timestamps();

    $table->unique(['bank_id', 'balance_date']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_daily_balances');
    }
};
