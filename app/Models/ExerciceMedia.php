<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciceMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercice_id',
        'media_type',
        'media_path',
        'creator_id',
        'updater_id',
        'order',
    ];

    public function exercice()
    {
        return $this->belongsTo(Exercice::class);
    }
}
