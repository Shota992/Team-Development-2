<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponseDetail extends Model
{
    use HasFactory;
    protected $table = 'survey_response_details';

    protected $fillable = [
        'response_id',
        'question_id',
        'question_text',
        'survey_question_option_id',
        'answer',
        'rating',
        'free_text',
    ];

    // リレーション: 所属する回答
    public function surveyResponse()
    {
        return $this->belongsTo('App\Models\SurveyResponse');
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }

    public function surveyResponseOptionDetail()
    {
        return $this->hasMany('App\Models\SurveyResponseOptionDetail');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    public function optionDetails()
    {
        return $this->hasMany(\App\Models\SurveyResponseOptionDetail::class, 'response_detail_id');
    }
}
