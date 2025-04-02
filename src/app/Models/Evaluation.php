<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'measure_Id',
        'keep',
        'try',
        'problem',
        'created_at',
        'updated_at',
    ];

    public function measure()
    {
        return $this->belongsTo(Measure::class);
    }

    public function evaluationTask()
    {
        return $this->hasMany(EvaluationTask::class);
    }
}
