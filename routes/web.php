<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;

// Change 'welcome' to 'converter'
Route::get('/', [CurrencyController::class, 'index']);

Route::post('/convert', [CurrencyController::class, 'convert'])->name('convert');