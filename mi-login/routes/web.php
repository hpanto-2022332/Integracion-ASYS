<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));

//para usuarios sin cuenta
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Formulario de asistencias (consume la API externa desde el servidor)
    Route::get('/asistencias', [AsistenciaController::class, 'form'])->name('asistencias');
    Route::get('/asistencias/categorias/{plan}', [AsistenciaController::class, 'categorias'])
        ->whereNumber('plan');
    Route::post('/asistencias', [AsistenciaController::class, 'crear']);
    Route::post('/asistencias/reiniciar-token', [AsistenciaController::class, 'reiniciarToken']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});