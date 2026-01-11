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
    Schema::create('gateway_daily_reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('gateway_provider_id')->constrained()->cascadeOnDelete();
        $table->date('report_date');
        
        $table->decimal('amount_collected', 15, 2)->default(0);
        $table->decimal('amount_settled', 15, 2)->default(0);
        $table->decimal('amount_unsettled', 15, 2)->default(0); // Pending settlement

        $table->timestamps();

        // Prevent duplicate reports for the same gateway on the same day
        $table->unique(['gateway_provider_id', 'report_date']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateway_daily_reports');
    }
};
