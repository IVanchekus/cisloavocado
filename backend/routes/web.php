<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DictionariesController;
use App\Http\Controllers\InformaticsController;
use App\Http\Controllers\NavController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TrainingOptionController;
use App\Http\Controllers\UsersController;
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
  Route::get('dictionaries/roles', [DictionariesController::class, 'roles']);
  Route::get('dictionaries/permissions', [DictionariesController::class, 'permissions']);

  Route::middleware('permission:users.view')->group(function () {
    Route::get('users', [UsersController::class, 'index']);
  });

  Route::prefix('admin')->group(function () {
    Route::middleware('permission:users.manage')->group(function () {
      Route::post('users', [UsersController::class, 'store']);
      Route::put('users/{id}/roles', [UsersController::class, 'syncRoles']);
    });

    Route::middleware('permission:roles.manage')->group(function () {
      Route::get('roles', [RolesController::class, 'index']);
      Route::post('roles', [RolesController::class, 'store']);
      Route::put('roles/{id}/permissions', [RolesController::class, 'syncPermissions']);
    });
  });

  Route::prefix('informatics')->group(function () {
    Route::get('exercises', [InformaticsController::class, 'getAllExercises']);
    Route::get('getTrainingOptions', [InformaticsController::class, 'getTrainingOptions']);
    Route::get('getTrainingOptionsByUser', [InformaticsController::class, 'getTrainingOptionsByUser']);
  });
 
  Route::prefix('training-option')->group(function () {
    Route::get('getExercises/{hash}', [TrainingOptionController::class, 'getExercises']);
    Route::post('saveAnswers', [TrainingOptionController::class, 'saveAnswers']);
    Route::get('exercise/{id}', [TrainingOptionController::class, 'getExercise']);
    Route::get('{id}', [TrainingOptionController::class, 'getTrainingOption']);
    Route::middleware('role:admin,teacher')->group(function () {
      Route::post('create', [TrainingOptionController::class, 'createTrainingOption']);
      Route::put('update/{id}', [TrainingOptionController::class, 'updateTrainingOption']);
    });
    Route::prefix('exercise')->group(function () {
      Route::middleware('permission:exercises.create')->group(function () {
        Route::post('create', [TrainingOptionController::class, 'createExercise']);
        Route::put('update/{id}', [TrainingOptionController::class, 'updateExercise']);
      });
    });
  });

  Route::prefix('profile')->group(function() {
    Route::get('user-data/{id}', [ProfileController::class, 'getUserData']);
    Route::get('exercises', [ProfileController::class, 'getMyExercises']);
    Route::get('solved-training-options', [ProfileController::class, 'getMySolvedTrainingOptions']);
  });
});

