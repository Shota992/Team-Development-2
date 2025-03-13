<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'type',
        'survey_id',
    ];

    public function survey()
    {
        return $this->belongsTo('App\Models\Survey');
    }

    public function surveyQuestionOption()
    {
        return $this->hasMany('App\Models\SurveyQuestionOption');
    }
}
