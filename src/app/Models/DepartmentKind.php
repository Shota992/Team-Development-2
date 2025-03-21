<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentKind extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function department()
    {
        return $this->hasMany('App\Models\Department');
    }
}
