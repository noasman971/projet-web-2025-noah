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
        Schema::create('questions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->timestamps();
            $table->foreignId('bilans_id')->constrained('cohorts_bilans')->onDelete('cascade');
            $table->string('question');
            $table->string('level');
            $table->string('answer_0', 500);
            $table->string('answer_1', 500)->nullable();
            $table->string('answer_2', 500)->nullable();
            $table->string('answer_3', 500)->nullable();
            $table->string('correct_answer', 500);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
