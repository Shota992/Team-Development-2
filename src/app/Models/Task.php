<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'measure_id',
        'name',
        'department_id',
        'user_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function measure()
    {
        return $this->belongsTo(Measure::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluationTask()
    {
        return $this->hasMany(EvaluationTask::class);
    }
}
