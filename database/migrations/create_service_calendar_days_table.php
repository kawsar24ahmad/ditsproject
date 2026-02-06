<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_calendar_days', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Logical day number (Day 1, Day 2...)
            $table->unsignedInteger('day_number');


            $table->text('notes')->nullable();

            // For drag & drop sorting later
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            // Prevent duplicate day numbers per service
            $table->unique(['service_id', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_calendar_days');
    }
};
