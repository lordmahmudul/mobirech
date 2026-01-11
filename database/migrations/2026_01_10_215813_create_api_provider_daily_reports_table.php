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
        Schema::create('api_provider_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_provider_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');
            
            $table->decimal('balance_added', 15, 2)->default(0);
            $table->decimal('balance_used', 15, 2)->default(0);
            $table->decimal('available_balance', 15, 2); // Closing balance

            $table->timestamps();

            // Prevent duplicate reports for the same provider on the same day
            $table->unique(['api_provider_id', 'report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_provider_daily_reports');
    }
};
