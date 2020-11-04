<?php

use App\Http\Controllers\FieldController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('cancha-uno', 'chanchas.uno');
Route::view('cancha-dos', 'chanchas.dos');

Route::resource('campo-uno', FieldController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
