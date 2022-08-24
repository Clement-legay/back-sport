<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuscleMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'muscle_id',
        'media_type',
        'media_path',
        'creator_id',
        'updater_id',
        'order',
    ];

    public function muscle()
    {
        return $this->belongsTo(Muscle::class);
    }
}
