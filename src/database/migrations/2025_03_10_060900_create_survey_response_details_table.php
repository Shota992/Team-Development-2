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
        Schema::create('survey_response_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id');
            $table->foreign('response_id')->references('id')->on('survey_responses');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('survey_questions');
            $table->tinyInteger('rating');
            $table->string('free_text' , 1000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_response_details');
    }
};
