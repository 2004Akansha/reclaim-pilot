<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_followups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recovery_case_id')
                ->constrained('recovery_cases')
                ->cascadeOnDelete();

            $table->text('message');

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_followups');
    }
};