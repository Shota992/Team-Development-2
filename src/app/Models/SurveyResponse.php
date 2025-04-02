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
        'free_message'
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function surveyQuestion()
    {
        return $this->belongsTo('App\Models\SurveyQuestion');
    }


    public function surveyResponseDetail()
    {
        return $this->hasMany('App\Models\SurveyResponseDetail');
    }

    public function details(): HasMany
    {
        return $this->hasMany(SurveyResponseDetail::class, 'response_id');
    }
}
