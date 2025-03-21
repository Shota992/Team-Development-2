<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'measure_id', 'name', 'start_date', 'end_date', 'status'
    ];

    // Measureとのリレーション (1対多)
    public function measure()
    {
        return $this->belongsTo(Measure::class);
    }
}
