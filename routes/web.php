<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentMarkListController;

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

Route::get('students/lists', [StudentController::class, 'getAll'])->name('students.lists');
Route::resource('students', StudentController::class)->only(['index','store','show','destroy']);
Route::resource('marklists', StudentMarkListController::class)->only(['index','store','show','destroy']);

