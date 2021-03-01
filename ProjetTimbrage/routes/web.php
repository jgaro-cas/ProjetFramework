<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClockingController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DepartmentController;

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
    return Inertia::render('Welcome', [
      'canLogin' => Route::has('login'),
      'canRegister' => Route::has('register'),
      'laravelVersion' => Application::VERSION,
      'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

/*
Route::get('departments', 'App\Http\Controllers\DepartmentController@index')->name('departments');
Route::get('departments/{id}', 'App\Http\Controllers\DepartmentController@show')->name('departments.show');
*/

Route::resource('departments', DepartmentController::class)->except(['edit']);

Route::resource('clockings', ClockingController::class)->except(['edit', 'create']);

Route::resource('projects', ProjectController::class)->except(['edit']);

// Assignments
Route::get('/user', [AssignmentController::class, 'index']);
Route::patch('/user', [AssignmentController::class, 'edit']);
Route::post('/user', [AssignmentController::class, 'create']);
Route::delete('/user', [AssignmentController::class, 'destroy']);