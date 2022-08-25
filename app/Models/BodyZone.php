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
        'thumbnail_path',
        'creator_id',
        'updater_id',
    ];

    public function muscles()
    {
        return $this->hasMany(Muscle::class)->get();
    }

    public function media()
    {
        return $this->hasMany(BodyZoneMedia::class);
    }

    public function orderMedia()
    {
        $count = $this->media()->count();
        return $count;
    }
}
