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
        'submitted_at',
    ];

    // リレーション: 回答の詳細 (質問ごとの回答)
    public function responseDetails()
    {
        return $this->hasMany(SurveyResponseDetail::class, 'response_id');
    }

    // リレーション: 所属するアンケート
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}
