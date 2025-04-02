<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->hasMany('App\Models\User');
    }

    public function departmentKind()
    {
        return $this->belongsTo('App\Models\DepartmentKind');
    }

    public function survey()
    {
        return $this->belongsToMany('App\Models\Survey');
    }

    public function office()
    {
        return $this->belongsTo('App\Models\Office');
    }

    // 修正：外部キーとして 'department_id' を明示的に指定する
    public function surveyQuestion()
    {
        return $this->hasMany('App\Models\SurveyQuestion', 'department_id');
    }
}
