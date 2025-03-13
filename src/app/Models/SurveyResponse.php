<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'user_id',
        'free_message'
    ];

    public function survey()
    {
        return $this->belongsTo('App\Models\Survey');
    }

    public function surveyQuestion()
    {
        return $this->belongsTo('App\Models\SurveyQuestion');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function surveyResponseDetail()
    {
        return $this->hasMany('App\Models\SurveyResponseDetail');
    }
}
