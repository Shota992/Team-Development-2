<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestionOption extends Model
{
    use HasFactory;

    protected $table = 'survey_question_options';

    protected $fillable = [
        'question_id',
        'text',
    ];

    /**
     * 紐づく設問（SurveyQuestion）
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    /**
     * この選択肢に対する回答（複数）
     */
    public function surveyResponseOptionDetails()
    {
        return $this->hasMany(\App\Models\SurveyResponseOptionDetail::class, 'option_id');
    }
}
