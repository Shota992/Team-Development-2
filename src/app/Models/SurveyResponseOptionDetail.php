<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponseOptionDetail extends Model
{
    use HasFactory;
    protected $table = 'survey_response_option_details';

    
    protected $fillable = [
        'response_detail_id', 'option_id'
    ];

    public function surveyResponseDetail()
    {
        return $this->belongsTo('App\Models\SurveyResponseDetail');
    }
    public function responseDetail(): BelongsTo
    {
        return $this->belongsTo(SurveyResponseDetail::class, 'response_detail_id');
    }

    public function surveyQuestionOption()
    {
        return $this->belongsTo('App\Models\SurveyQuestionOption');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SurveyQuestionOption::class, 'option_id');
    }
}
