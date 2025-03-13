<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyQuestionOption extends Model
{

    use HasFactory;

    protected $table = 'survey_question_options';

    protected $fillable = [
        'option',
        'survey_question_id',
        'question_id', 'text'
    ];

    public function surveyQuestion()
    {
        return $this->belongsTo('App\Models\SurveyQuestion');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    public function surveyResponseOptionDetail()
    {
        return $this->hasMany('App\Models\SurveyResponseOptionDetail');

    }
}
