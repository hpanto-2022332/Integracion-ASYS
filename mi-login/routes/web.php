<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));

// Rutas de autenticación para usuarios invitados (sin cuenta)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas generales para cualquier usuario autenticado (Clientes y Administradores)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Formulario operativo de asistencias e integración con el Gateway externo
    Route::get('/asistencias', [AsistenciaController::class, 'form'])->name('asistencias');
    Route::get('/asistencias/categorias/{plan}', [AsistenciaController::class, 'categorias'])->whereNumber('plan');
    Route::post('/asistencias', [AsistenciaController::class, 'crear']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

//  MÓDULO EXCLUSIVO DE ADMINISTRACIÓN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/usuarios', [AuthController::class, 'indexUsuarios'])->name('admin.usuarios');
    Route::post('/admin/usuarios/crear', [AuthController::class, 'crearUsuarioAdmin'])->name('admin.usuarios.crear');
    Route::post('/admin/usuarios/{id}/cambiar-rol', [AuthController::class, 'cambiarRol'])->name('admin.usuarios.cambiarRol');
    Route::get('/admin/bitacora', [AuthController::class, 'indexBitacora'])->name('admin.bitacora');
});
