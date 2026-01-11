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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        
        // Link to a category (e.g., Marketing)
        $table->foreignId('expense_category_id')->constrained()->cascadeOnDelete();
        
        // Optional: Link to a Bank (Which account paid for this?)
        $table->foreignId('bank_id')->nullable()->constrained()->nullOnDelete();

        $table->date('expense_date');
        $table->decimal('amount', 15, 2);
        $table->string('description')->nullable();
        $table->string('reference_no')->nullable(); // e.g., Invoice #123
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
