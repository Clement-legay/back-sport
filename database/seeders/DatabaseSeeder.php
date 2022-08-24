<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\BodyZone;
use App\Models\Exercice;
use App\Models\ExerciceRelation;
use App\Models\Muscle;
use App\Models\User;
use Couchbase\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('users')->delete();
//        DB::table('muscles')->delete();
//        DB::table('exercices')->delete();
//        DB::table('exercice_relations')->delete();
//        DB::table('programmes')->delete();
//        DB::table('body_zones')->delete();

        User::factory(10)->create();
        BodyZone::factory(10)->create();
        Muscle::factory(10)->create();
        Exercice::factory(10)->create();
        ExerciceRelation::factory(10)->create();
    }
}
