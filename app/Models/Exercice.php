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
        'creator_id',
        'updater_id',
    ];

    public function muscles()
    {
        return $this->hasManyThrough(Muscle::class, ExerciceRelation::class, 'exercice_id', 'id', 'id', 'muscle_id');
    }

    public function muscleRelations()
    {
        return $this->hasMany(ExerciceRelation::class, 'exercice_id');
    }

    public function muscleRelation($id)
    {
        return $this->hasMany(ExerciceRelation::class, 'exercice_id')->where('muscle_id', $id);
    }

    public function assignToMuscle($muscle)
    {
        $exerciceRelation = new ExerciceRelation([
            'exercice_id' => $this->id,
            'muscle_id' => $muscle->id,
        ]);

        $exerciceRelation->save();
    }

    public function discardMuscles($muscle = null)
    {
        if ($muscle) {
            $this->muscleRelation($muscle->id)->delete();
        } else {
            $this->muscleRelations()->delete();
        }
    }
}
