<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'days_in_week',
        'focus',
        'exercices',
        'duration_goal',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->first();
    }
}
