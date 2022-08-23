<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciceRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercice_id',
        'muscle_id',
    ];
}
