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

    // フィールドの修正 (title を追加し、不要なカラムを削除)
    protected $fillable = [
        'survey_id',
        'title',       // ← ここを追加
        'text',
        'description',
        'common_status'
    ];

    /**
     * この設問が属するアンケート（個別設問の場合のみ）
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Survey::class, 'survey_id');
    }

    /**
     * この設問に紐づく選択肢（存在する場合）
     */
    public function surveyQuestionOptions(): HasMany
    {
        return $this->hasMany(\App\Models\SurveyQuestionOption::class, 'question_id');
    }

    /**
     * この設問に対する回答詳細
     */
    public function responseDetails(): HasMany
    {
        return $this->hasMany(\App\Models\SurveyResponseDetail::class, 'question_id');
    }

    /**
     * Scope: 共通設問（survey_idがnull）を取得する
     */
    public function scopeCommon($query)
    {
        return $query->whereNull('survey_id');
    }

    /**
     * Scope: アンケート固有の設問を取得する
     *
     * @param int $surveyId
     */
    public function scopeSpecific($query, int $surveyId)
    {
        return $query->where('survey_id', $surveyId);
    }
}
