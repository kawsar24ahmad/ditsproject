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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('site_url')->nullable();
            $table->string('logo')->nullable();      // store logo filename/path
            $table->string('favicon')->nullable();   // store favicon filename/path

            // Payment info
            $table->string('bkash_account_no')->nullable();
            $table->string('nagad_account_no')->nullable();
            $table->string('bkash_type')->nullable();
            $table->string('nagad_type')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_branch')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
