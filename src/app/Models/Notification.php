<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'read_at',
    ];

    // 通知が所属するユーザーとのリレーションを定義する場合
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
