<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screenshot_analyses', function (Blueprint $table) {

            $table->id();

            $table->foreignId('case_screenshot_id')
                ->constrained('case_screenshots')
                ->cascadeOnDelete();

            $table->string('detected_issue')->nullable();

            $table->text('visible_message')->nullable();

            $table->text('possible_reason')->nullable();

            $table->unsignedTinyInteger('confidence')
                ->default(0);

            $table->json('raw_analysis')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screenshot_analyses');
    }
};