<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponseDetail extends Model
{
    protected $table = 'survey_response_details';

    protected $fillable = [
        'response_id', 'question_id', 'rating', 'free_text'
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
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
