<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
    ];

    // リレーション: このアンケートに対する回答
    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class, 'survey_id');
    }
}
