<?php

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
    return view('welcome');
});

Route::middleware('web')->group( function () { 
Route::get('/experiences' , [App\Http\Controllers\Admin\Api\ExperienceController::class , 'index']);
Route::get('/formations' , [App\Http\Controllers\Admin\Api\FormationController::class , 'index']);
Route::get('/projects' , [App\Http\Controllers\Admin\Api\ProjectController::class , 'index']);
Route::get('/clients' , [App\Http\Controllers\Admin\Api\ClientController::class , 'index']);
});




// Route::get('/dashboard', function () {
//     return view('welcome');
// })->middleware(['auth'])->name('dashboard');

// require __DIR__.'/auth.php';
