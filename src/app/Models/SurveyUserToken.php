<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyUserToken extends Model
{
    use HasFactory;

    public $timestamps = false; // timestampsを無効化
    protected $fillable = [
        'survey_id',
        'user_id',
        'token',
        'answered',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}