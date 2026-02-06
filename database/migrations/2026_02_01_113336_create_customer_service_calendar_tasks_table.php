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
        Schema::create('customer_service_calendar_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_day_id')->constrained('customer_service_calendar_days')->onDelete('cascade');
            $table->foreignId('service_task_id')->nullable()->constrained('service_calendar_tasks')->onDelete('set null');
            $table->string('title');
            $table->enum('status', ['pending','in_progress','completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_service_calendar_tasks');
    }
};
