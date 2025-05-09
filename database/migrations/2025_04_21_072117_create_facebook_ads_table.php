<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facebook_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_transaction_id')->nullable()->constrained()->onDelete('cascade');

            $table->text('page_link');
            $table->decimal('budget', 10, 2);
            $table->integer('duration');
            $table->integer('min_age');
            $table->integer('max_age');
            $table->string('location');
            $table->string('button')->nullable();
            $table->text('greeting')->nullable();

            $table->string('status')->default('pending');
             // Add foreign key constraint
             $table->foreignId('facebook_page_id')->nullable()->constrained('facebook_pages')->nullOnDelete();
            $table->string('url')->nullable();
            $table->string('number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_ads');
    }
};
