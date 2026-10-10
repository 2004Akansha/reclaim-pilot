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
        Schema::table('recovery_cases', function (Blueprint $table) {
            /*
            |--------------------------------------------------------------------------
            | Remove the existing foreign key
            |--------------------------------------------------------------------------
            */

            $table->dropForeign(['user_id']);
        });

        Schema::table('recovery_cases', function (Blueprint $table) {
            /*
            |--------------------------------------------------------------------------
            | Make user_id optional
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->change();
        });

        Schema::table('recovery_cases', function (Blueprint $table) {
            /*
            |--------------------------------------------------------------------------
            | Add the foreign key again
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recovery_cases', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('recovery_cases', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')
                ->nullable(false)
                ->change();
        });

        Schema::table('recovery_cases', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};