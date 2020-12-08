<?php

use App\Http\Controllers\FieldController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('cancha-uno', 'canchas.uno')
					->name('cancha-uno')
					->middleware('auth');
Route::view('cancha-dos', 'canchas.two')
					->name('cancha-dos')
					->middleware('auth');

Route::resource('onefields', FieldController::class)
					->except(['create', 'edit']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
