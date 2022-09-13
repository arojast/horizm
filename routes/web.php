<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\UsersController;

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

Route::get('/posts/import', [PostsController::class,'import']);
Route::get('/posts/top', [PostsController::class,'top']);
Route::get('/posts/{id}', [PostsController::class,'show']);

Route::get('/users/import', [UsersController::class,'import']);
Route::get('/users', [UsersController::class,'index']);
