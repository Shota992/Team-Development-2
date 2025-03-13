<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestion extends Model
{
    protected $table = 'survey_questions';

    protected $fillable = [
        'survey_id', 'text', 'order'
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function responseDetails(): HasMany
    {
        // 質問に紐づく回答詳細
        return $this->hasMany(SurveyResponseDetail::class, 'question_id');
    }
}
