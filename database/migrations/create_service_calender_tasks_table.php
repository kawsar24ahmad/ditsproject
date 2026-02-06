<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_calender_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_calendar_day_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();


            $table->enum('status', [
                'pending',
                'in_progress',
                'completed'
            ])->default('pending');



            // For task ordering inside a day
            $table->unsignedInteger('sort_order')->default(0);



            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_calender_tasks');
    }
};
