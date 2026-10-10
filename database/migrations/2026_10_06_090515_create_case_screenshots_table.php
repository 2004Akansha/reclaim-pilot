<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_screenshots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recovery_case_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('file_path');

            $table->string('original_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_screenshots');
    }
};