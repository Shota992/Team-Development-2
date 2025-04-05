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
    protected $casts = [
        'start_date' => 'datetime:Y-m-d H:i', // 時分までフォーマット
        'end_date' => 'datetime:Y-m-d H:i',   // 時分までフォーマット
    ];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    // リレーション: このアンケートに対する回答
    public function surveyResponses()
    {
        return $this->hasMany('App\Models\SurveyResponse');
    }

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

    // SurveyUserTokenとのリレーションを追加
    public function surveyUserTokens()
    {
        return $this->hasMany(\App\Models\SurveyUserToken::class, 'survey_id');
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