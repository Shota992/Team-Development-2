<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'option',
        'survey_question_id',
    ];

    public function surveyQuestion()
    {
        return $this->belongsTo('App\Models\SurveyQuestion');
    }

    public function surveyResponseOptionDetail()
    {
        return $this->hasMany('App\Models\SurveyResponseOptionDetail');
    }
}
