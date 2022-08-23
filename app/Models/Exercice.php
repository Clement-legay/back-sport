<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'fat_burn',
        'level',
        'type',
    ];

    public function muscles()
    {
        return $this->hasManyThrough(Muscle::class, ExerciceRelation::class);
    }

    public function muscle($id)
    {
        return $this->muscles()->where('muscle_id', $id)->first();
    }

    public function assignToMuscle($muscle)
    {
        $exerciceRelation = new ExerciceRelation([
            'exercice_id' => $this->id,
            'muscle_id' => $muscle->id,
        ]);

        $exerciceRelation->save();
    }

    public function discardFromMuscles($muscle = null)
    {
        if ($muscle) {
            $this->muscle($muscle->id)->delete();
        } else {
            $this->muscles()->delete();
        }
    }
}
