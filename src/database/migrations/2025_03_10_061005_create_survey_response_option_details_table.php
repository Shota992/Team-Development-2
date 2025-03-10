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
        Schema::create('survey_response_option_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_detail_id');
            $table->foreign('response_detail_id')->references('id')->on('survey_response_details');
            $table->unsignedBigInteger('option_id');
            $table->foreign('option_id')->references('id')->on('survey_question_options');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_response_option_details');
    }
};
