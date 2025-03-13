<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponseOptionDetail extends Model
{
    protected $table = 'survey_response_option_details';

    protected $fillable = [
        'response_detail_id', 'option_id'
    ];

    public function responseDetail(): BelongsTo
    {
        return $this->belongsTo(SurveyResponseDetail::class, 'response_detail_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SurveyQuestionOption::class, 'option_id');
    }
}
