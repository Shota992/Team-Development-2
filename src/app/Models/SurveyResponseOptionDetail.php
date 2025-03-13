<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponseOptionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_detail_id',
        'option_id'
    ];

    public function surveyResponseDetail()
    {
        return $this->belongsTo('App\Models\SurveyResponseDetail');
    }

    public function surveyQuestionOption()
    {
        return $this->belongsTo('App\Models\SurveyQuestionOption');
    }
}
