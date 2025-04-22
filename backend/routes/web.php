<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InformaticsController;
use App\Http\Controllers\NavController;
use App\Http\Controllers\TrainingOptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/login', [AuthController::class, 'login']);

  Route::middleware('auth')->group(function() {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
  });
});

Route::get('navs', [NavController::class, 'getNavs']);

Route::middleware('auth')->group(function() {
  Route::prefix('informatics')->group(function () {
    Route::get('exercises', [InformaticsController::class, 'getAllExercises']);
    Route::get('getTrainingOptions', [InformaticsController::class, 'getTrainingOptions']);
  });

  Route::prefix('training-option')->group(function () {
    Route::get('getExercises/{hash}', [TrainingOptionController::class, 'getExercises']);
    Route::post('saveAnswers', [TrainingOptionController::class, 'saveAnswers']);
  });
});

