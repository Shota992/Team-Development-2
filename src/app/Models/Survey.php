<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'office_id',
        'department_id',
    ];
    protected $dates = ['start_date', 'end_date'];

        // アンケートに属する設問
        public function questions(): HasMany
        {
            return $this->hasMany(SurveyQuestion::class, 'survey_id');
        }

    // リレーション: このアンケートに対する回答
    public function surveyResponses()
    {
        return $this->hasMany('App\Models\SurveyQuestion');
    }

        // このアンケートに対する回答
        public function responses(): HasMany
        {
            return $this->hasMany(SurveyResponse::class, 'survey_id');
        }

    public function office()
    {
        return $this->belongsTo('App\Models\Office');
    }

    public function department()
    {
        return $this->belongsToMany('App\Models\Department');
    }
}
