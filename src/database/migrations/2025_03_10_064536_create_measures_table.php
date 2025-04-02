<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('measures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('department_id');
            $table->string('title');
            $table->text('description');
            $table->integer('status')->default(0); // デフォルト値を0に設定
            $table->integer('evaluation_interval_value')->nullable(); // 例: 2
            $table->string('evaluation_interval_unit')->nullable(); // 'weeks' または 'months'
            $table->tinyInteger('evaluation_status');
            $table->date('next_evaluation_date')->nullable(); // 次回評価日
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measures');
    }
};
