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
//    $response = Http::post(route('users.register'), [
//        'first_name' => 'test',
//        'last_name' => 'test',
//        'email' => 'clementlgy76@gmail.com',
//        'password' => '123456',
//        'password_confirmation' => '123456',
//        'weight' => 80,
//        'will' => 'increase',
//    ]);
//
//    dd($response->json());
});

Route::get('/login', function () {
//    $response = Http::post(route('users.login'), [
//        'email' => 'clementlgy76@gmail.com',
//        'password' => '123456',
//    ]);
//
//    dd($response->json());
});

Route::get('/verify/{token}', [UserController::class, 'verify'])->name('verifyAccount');

Route::get('/remember', function () {
//    $response = Http::post(route('users.remember'), [
//        'remember_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0b2tlbiI6Ijc5MWRmMzM2ZjljZjgyMDhmNWYzZjk0MTU1ZDVlZjQwYWViY2FjYjFhNDY5NWFiZDQ4ZDg5MDc3NGI2ZWYyMGIiLCJpYXQiOjE2NjI3MTUxODQsImV4cCI6MTY2MzMxOTk4NH0.n8NvTV25YQPX8IKqtOQnMwAbdHqoirYwcI2sy4d0ZZ0',
//    ]);
//
//    dd($response->json());
});
