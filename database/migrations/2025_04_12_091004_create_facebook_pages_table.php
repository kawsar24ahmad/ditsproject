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
        Schema::create('facebook_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('page_id')->unique();
            $table->string('page_name');
            $table->string('category')->nullable();
            $table->text('page_access_token');
            $table->text('profile_picture')->nullable();
            $table->text('cover_photo')->nullable();
            $table->string('status')->default('pending');
            $table->string('page_username')->nullable();
            $table->integer('likes')->nullable();
            $table->integer('followers')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_pages');
    }
};
