<?php

use App\Http\Controllers\FieldController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('cancha-uno', 'chanchas.uno')->middleware('auth');
Route::view('cancha-dos', 'chanchas.dos')->middleware('auth');

Route::resource('onefields', FieldController::class)
		->except(['create', 'edit']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
