<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'question_text',
        'rating',
        'free_text',
    ];

    // リレーション: 所属する回答
    public function surveyResponse()
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }
}
