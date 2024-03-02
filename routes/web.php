<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\SmsController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ImageController::class, 'index']);
Route::post('/upload', [ImageController::class, 'upload']);
Route::post('/delete/{id}', [ImageController::class, 'delete']);
Route::post('/update/{id}', [ImageController::class, 'update']);
Route::get('/images', [ImageController::class, 'images']);