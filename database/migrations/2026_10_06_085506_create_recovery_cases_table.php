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
        Schema::create('recovery_cases', function (Blueprint $table) {
            $table->id();

            $table->string('issue_type');
            $table->text('description')->nullable();

            $table->string('username')->nullable();
            $table->string('email')->nullable();

            $table->string('account_type')->nullable();

            $table->string('status')->default('diagnosing');

            $table->string('possible_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recovery_cases');
    }
};