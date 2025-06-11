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
        Schema::create('service_assigns', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('employee_id')->nullable(); // Optional

            // Price of the service assignment
            $table->decimal('price', 10, 2);

            // Payments
            $table->decimal('paid_payment', 10, 2)->default(0);

            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->date('delivery_date')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('customer_id');
            $table->index('employee_id');
            $table->index('service_id');

            // Foreign key constraints
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_assigns');
    }
};
