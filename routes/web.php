<?php

use App\Http\Controllers\UserController;
use App\Models\Exercice;
use App\Models\User;
use Illuminate\Support\Facades\Http;
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

Route::get('/', function () {

});

Route::get('/login', function () {

});

Route::get('/verify/{token}', [UserController::class, 'verify'])->name('verifyAccount');

Route::get('/remember', function () {

});
