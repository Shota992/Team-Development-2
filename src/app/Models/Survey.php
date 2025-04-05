<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

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

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
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
        return $this->belongsTo(\App\Models\Department::class, 'department_id');
    }
    public function token()
    {
        return $this->hasMany('App\Models\SurveyResponseUser');
    }
    public function getDateStatusAttribute()
    {
        $today = Carbon::today();

        if ($this->start_date && $today->lt(Carbon::parse($this->start_date))) {
            return 1; // 開始日がまだ来ていない
        }

        if ($this->end_date && $today->gt(Carbon::parse($this->end_date))) {
            return 2; // 終了日を過ぎている
        }

        return 0; // 通常状態
    }
}
