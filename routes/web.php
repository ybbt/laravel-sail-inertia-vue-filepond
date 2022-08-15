<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TempFileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
//    return view('welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/upload', [FileController::class, 'index'])->middleware('auth');
Route::post('/upload', [TempFileController::class, 'upload'])->middleware('auth');
Route::delete('/upload', [TempFileController::class, 'delete'])->middleware('auth');
Route::post('/save', [FileController::class, 'store'])->middleware('auth');
Route::post('/edit/{file}', [FileController::class, 'update'])->middleware('auth')->can('update', 'file');
Route::delete('/delete/{file}', [FileController::class, 'destroy'])->middleware('auth')->can('delete', 'file');


require __DIR__.'/auth.php';
