<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FieldController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('profile', [ProfileController::class, 'edit'])
			->name('profile.edit');

Route::put('profile', [ProfileController::class, 'update'])
			->name('profile.update');

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
