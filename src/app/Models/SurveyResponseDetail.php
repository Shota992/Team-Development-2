<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'question_id',
        'survey_question_option_id',
        'answer',
    ];

    public function surveyResponse()
    {
        return $this->belongsTo('App\Models\SurveyResponse');
    }

    public function surveyResponseOptionDetail()
    {
        return $this->hasMany('App\Models\SurveyResponseOptionDetail');
    }
}
