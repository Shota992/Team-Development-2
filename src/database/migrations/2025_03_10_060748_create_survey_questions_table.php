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
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id')->nullable();
            $table->string('title');
            $table->string('text');
            $table->string('description' , 1000); //項目に関する説明文
            $table->boolean('common_status'); //true:共通項目 false:アンケート別独自項目
            $table->unsignedBigInteger('office_id')->nullable(); //アンケート独自項目時のみ追加
            $table->foreign('office_id')->references('id')->on('offices')->nullable(); //アンケート独自項目時のみ追加
            $table->unsignedBigInteger('department_id')->nullable(); //アンケート独自項目時のみ追加
            $table->foreign('department_id')->references('id')->on('departments')->nullable(); //アンケート独自項目時のみ追加
            $table->boolean('display_status'); //true:有効 false:無効
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
