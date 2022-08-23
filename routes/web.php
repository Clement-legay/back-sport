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

Route::get('/', function () {
    $exercice = Exercice::find(3);
    $muscles = $exercice->muscleRelations()->get();
    dd($muscles);
});
