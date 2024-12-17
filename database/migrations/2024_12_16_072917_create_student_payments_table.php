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
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->constrained('student_records')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // mpesa, stripe, bank
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending'); // pending, successful, failed
            $table->text('description')->nullable(); // Fee details or purpose
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};
