<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyZoneMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'body_zone_id',
        'media_type',
        'media_path',
        'creator_id',
        'updater_id',
        'order',
    ];

    public function bodyZone()
    {
        return $this->belongsTo(BodyZone::class);
    }
}
