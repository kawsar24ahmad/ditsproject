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
        Schema::create('service_task_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_assign_id')
                ->constrained('service_assigns')
                ->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('users', 'id')->onDelete('cascade');
            $table->date('date');
            $table->text('work_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_task_reports');
    }
};
