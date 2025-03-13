<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SurveyQuestion extends Model
{
    use HasFactory;
    protected $table = 'survey_questions';
    protected $fillable = [
        'question',
        'type',
        'survey_id',
        'text', 'order'
    ];

    public function survey()
    {
        return $this->belongsTo('App\Models\Survey');
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function surveyQuestionOption()
    {
        return $this->hasMany('App\Models\SurveyQuestionOption');
    }

    public function responseDetails(): HasMany
    {
        // 質問に紐づく回答詳細
        return $this->hasMany(SurveyResponseDetail::class, 'question_id');
    }
}
