<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'upper_body',
        'region',
        'creator_id',
        'updater_id',
    ];

    public function muscles()
    {
        return $this->hasMany(Muscle::class)->get();
    }
}
