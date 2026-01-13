<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_recharge_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_provider_id')->constrained('api_providers')->onDelete('cascade');
            $table->date('report_date');
            
            // API Stats
            $table->decimal('api_success_amount', 15, 2)->default(0); // Total Amount
            $table->integer('api_success_count')->default(0);         // Total Count
            
            // DB Stats
            $table->decimal('db_success_amount', 15, 2)->default(0);  // Total Amount
            $table->integer('db_success_count')->default(0);          // Total Count
            
            $table->timestamps();

            // Prevent duplicate entries for the same provider on the same day
            $table->unique(['api_provider_id', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_recharge_stats');
    }
};