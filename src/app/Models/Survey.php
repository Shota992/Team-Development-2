<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'office_id',
        'department_id',
    ];

    public function surveyQuestion()
    {
        return $this->hasMany('App\Models\SurveyQuestion');
    }

    public function office()
    {
        return $this->belongsTo('App\Models\Office');
    }

    public function department()
    {
        return $this->belongsToMany('App\Models\Department');
    }
}
