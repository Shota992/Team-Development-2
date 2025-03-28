<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id', 'department_id', 'title', 'description', 'status', 'evaluation_interval', 'evaluation_status'
    ];

    // MeasureとTaskのリレーション (1対多)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // MeasureとEvaluationのリレーション (1対多)
    public function evaluation()
    {
        return $this->hasMany(Evaluation::class);
    }
}
