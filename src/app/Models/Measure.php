<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'department_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'evaluation_interval',
        'evaluation_status',
        'next_evaluation_date',
    ];

    /**
     * この施策に紐づくタスク（1対多）
     */
    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class);
    }

    // MeasureとEvaluationのリレーション (1対多)
    public function evaluation()
    {
        return $this->hasMany(Evaluation::class);
    }

    // カスタムアクセサ: 評価回数
    public function getEvaluationCountAttribute()
    {
        return $this->evaluation->count();
    }

    // カスタムアクセサ: 最新の評価日
    public function getEvaluationLastDateAttribute()
    {
        return $this->evaluation->sortByDesc('created_at')->first()?->created_at;
    }
}
