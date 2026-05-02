<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('index'));
Route::fallback(fn() => view('index'));
