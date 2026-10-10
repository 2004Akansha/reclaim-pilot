<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analyses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recovery_case_id')
                ->constrained('recovery_cases')
                ->cascadeOnDelete();

            $table->string('issue_detected')->nullable();

            $table->string('possible_reason')->nullable();

            $table->decimal('confidence', 5, 2)->nullable();

            $table->text('summary')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analyses');
    }
};