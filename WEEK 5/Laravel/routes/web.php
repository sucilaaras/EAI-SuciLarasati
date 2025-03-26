<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BalitaController;

Route::get('/balita', [BalitaController::class, 'index']);
Route::get('/balita/{id}', [BalitaController::class, 'show']);
