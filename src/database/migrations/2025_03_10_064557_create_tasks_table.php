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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // 施策（measure）への外部キー
            $table->foreignId('measure_id')
                  ->constrained('measures')
                  ->cascadeOnDelete();

            // 部署への外部キー
            $table->foreignId('department_id')
                  ->constrained('departments')
                  ->cascadeOnDelete();

            // 担当者（ユーザー）への外部キー
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
