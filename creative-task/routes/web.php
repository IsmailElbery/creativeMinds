<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
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

Route::get('/', function () {
    return view('welcome');
})->middleware(['guest'])->name('welcome');

//routes for authentication
Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    //getLogin
    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    //getRegister
    Route::get('register', [AuthController::class,'registerForm'])->name('register');
    //get activate
    Route::get('activate', [AuthController::class,'activateForm'])->name('activate');
    Route::post('login', [AuthController::class, 'login'])->name('postLogin');

    Route::post('register', [AuthController::class,'register'])->name('postRegister');
    Route::post('activate', [AuthController::class,'activate'])->name('activate');

});
//dashboard and logout routes
Route::group(['middleware' => 'auth:web'], function () {
    Route::get('dashboard', [AuthController::class,'dashboard'])->name('dashboard');
    Route::get('logout', [AuthController::class,'logout'])->name('logout');

    Route::get('users', [UserController::class,'users'])->name('users');
    Route::get('add-user', [UserController::class,'addUserForm'])->name('addUser');
    Route::post('add-user', [UserController::class,'addUser'])->name('addUser');
    Route::get('edit-user/{id}', [UserController::class,'editUserForm'])->name('editUser');
    Route::post('edit-user/{id}', [UserController::class,'editUser'])->name('editUser');
    Route::get('activate-user/{id}', [UserController::class,'activateUser'])->name('activateUser');
    Route::get('delete-user/{id}', [UserController::class,'deleteUser'])->name('deleteUser');

});
