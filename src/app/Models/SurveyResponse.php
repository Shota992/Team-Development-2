<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyResponse extends Model
{
    protected $table = 'survey_responses';

    protected $fillable = [
        'survey_id',
        'user_id',
        'free_message'
    ];

    /**
     * この回答が属するアンケート
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    /**
     * この回答をしたユーザー
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * この回答に属する詳細回答（各設問への回答）
     */
    public function responseDetails(): HasMany
    {
        return $this->hasMany(SurveyResponseDetail::class, 'response_id');
    }
}
