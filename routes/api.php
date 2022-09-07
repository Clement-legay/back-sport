<?php

use App\Http\Controllers\BodyZoneController;
use App\Http\Controllers\ExerciceController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MuscleController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['as' => 'body-zones.', 'prefix' => 'body-zones'], function () {
    Route::get('/get', [BodyZoneController::class, 'getAll'])->name('getAll');

    Route::get('/{id}/get', [BodyZoneController::class, 'show'])->name('show');
    Route::get('/{id}/get-media', [BodyZoneController::class, 'getMedia'])->name('getMedia');
    Route::get('/{id}/muscles', [BodyZoneController::class, 'getMuscles'])->name('getMuscles');
    Route::post('/create', [BodyZoneController::class, 'store'])->name('store');
    Route::put('/{id}/update', [BodyZoneController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [BodyZoneController::class, 'destroy'])->name('destroy');
});

Route::group(['as' => 'workouts.', 'prefix' => 'workouts'], function () {
    Route::get('/get', [ExerciceController::class, 'getAll'])->name('getAll');

    Route::get('/{id}/get', [ExerciceController::class, 'show'])->name('show');
    Route::get('/{id}/get-media', [ExerciceController::class, 'getMedia'])->name('getMedia');
    Route::get('/{id}/muscles', [ExerciceController::class, 'muscles'])->name('muscles');
    Route::post('/create', [ExerciceController::class, 'store'])->name('store');
    Route::put('/{id}/update', [ExerciceController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [ExerciceController::class, 'destroy'])->name('destroy');
});

Route::group(['as' => 'muscles.', 'prefix' => 'muscles'], function () {
    Route::get('/get', [MuscleController::class, 'getAll'])->name('getAll');

    Route::get('/{id}/get', [MuscleController::class, 'show'])->name('show');
    Route::get('/{id}/get-media', [MuscleController::class, 'getMedia'])->name('getMedia');
    Route::get('/{id}/workouts', [MuscleController::class, 'getWorkouts'])->name('getWorkouts');
    Route::post('/create', [MuscleController::class, 'store'])->name('store');
    Route::put('/{id}/update', [MuscleController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [MuscleController::class, 'destroy'])->name('destroy');
});

Route::group(['as' => 'programmes.', 'prefix' => 'programmes'], function () {
    Route::get('/get', [ProgrammeController::class, 'getAll'])->name('getAll');

    Route::get('/{id}/get', [ProgrammeController::class, 'show'])->name('show');
    Route::post('/create', [ProgrammeController::class, 'store'])->name('store');
    Route::put('/{id}/update', [ProgrammeController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [ProgrammeController::class, 'destroy'])->name('destroy');
});

Route::group(['as' => 'users.', 'prefix' => 'users'], function () {
    Route::get('/get', [UserController::class, 'getAll'])->name('getAll');

    Route::get('/{id}/get', [UserController::class, 'show'])->name('show');
    Route::post('/create', [UserController::class, 'store'])->name('store');
    Route::put('/{id}/update', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('destroy');
});

Route::group(['as' => 'medias.', 'prefix' => 'medias'], function () {
    Route::post('{reference_type}/create', [MediaController::class, 'store'])->name('create');
    Route::delete('{reference_type}/{id}/delete', [MediaController::class, 'destroy'])->name('destroy');
    Route::patch('{reference_type}/{id}/change_order', [MediaController::class, 'changeOrder'])->name('changeOrder');
});
