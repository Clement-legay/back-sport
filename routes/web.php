<?php

use App\Models\Exercice;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/search/{id}', function ($id) {
    $exercice = Exercice::find($id);
    dump($exercice);
    $relations = $exercice->muscleRelations()->get();
    dump($relations);
    $muscles = $exercice->muscles()->get();
    dd($muscles);
});
