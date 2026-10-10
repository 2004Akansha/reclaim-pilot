<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evidence', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recovery_case_id')
                ->constrained('recovery_cases')
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->boolean('completed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidence');
    }
};