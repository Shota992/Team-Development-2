<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'task_id',
        'score',
        'comment',
        'created_at',
        'updated_at',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
