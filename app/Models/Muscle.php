<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muscle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'body_zone_id',
    ];

    public function exercices()
    {
        return $this->hasMany(Exercice::class)->get();
    }

    public function bodyZone()
    {
        return $this->belongsTo(BodyZone::class)->first();
    }
}
