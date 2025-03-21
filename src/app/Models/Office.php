<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->hasMany('App\Models\User');
    }

    public function survey()
    {
        return $this->hasMany('App\Models\Survey');
    }

    public function department()
    {
        return $this->hasMany('App\Models\Department');
    }

    public function surveyQuestion()
    {
        return $this->hasMany('App\Models\SurveyQuestion');
    }
}
