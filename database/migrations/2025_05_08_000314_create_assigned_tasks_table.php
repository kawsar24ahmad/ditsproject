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
        Schema::create('assigned_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_assign_id')->constrained()->onDelete('cascade');


            $table->foreignId('service_task_id')->nullable()->constrained()->onDelete('set null');


            $table->string('title');

            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_tasks');
    }
};
