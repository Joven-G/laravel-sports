<?php

use App\Http\Controllers\FieldController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('campo-uno', function () {
//     return view('fieldOne.index');
// });
Route::resource('campo-uno', FieldController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
