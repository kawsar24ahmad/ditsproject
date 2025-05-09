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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['recharge', 'payment', 'refund', 'bonus']);
            $table->decimal('amount', 12, 2);
            $table->string('method')->nullable(); // যেমন: sslcommerz, bkash, nagad, manual
            $table->string('payment_method')->nullable(); // যেমন: sslcommerz, bkash, nagad, manual
            $table->text('description')->nullable();
            $table->string('sender_number')->nullable(); // sender number for recharge
            $table->string('transaction_id')->nullable(); // transaction id for recharge
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
