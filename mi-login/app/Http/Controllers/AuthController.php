<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'pais'     => 'required|string|in:Guatemala,El Salvador',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'pais'     => $data['pais'],
        ]);

        $user->assignRole('cliente');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Login con rate limiting: máx. 10 intentos por minuto por IP+email.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // ── 🛡️ Rate Limiting ──────────────────────────────────────────────
        $key = 'login.' . Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withErrors(['email' => "Demasiados intentos. Intenta de nuevo en {$seconds} segundos."])
                ->onlyInput('email');
        }
        // ─────────────────────────────────────────────────────────────────

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key); // Éxito → limpiar contador
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.usuarios');
            }

            return redirect()->intended('dashboard');
        }

        RateLimiter::hit($key, 60); // Fallo → sumar intento (caduca en 60 s)

        return back()
            ->withErrors(['email' => 'Correo o contraseña incorrectos.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Panel de gestión de usuarios (solo admin).
     */
    public function indexUsuarios()
    {
        $usuarios = User::with('roles')->orderBy('created_at', 'desc')->get();
        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * Cambia el rol de un usuario entre admin ↔ cliente.
     * Incluye flash message de éxito/error.
     */
    public function cambiarRol(Request $request, int $id)
    {
        $usuario = User::findOrFail($id);

        // No permitir que el admin se cambie su propio rol
        if ($usuario->id === Auth::id()) {
            return redirect()->route('admin.usuarios')
                ->with('error', 'No puedes cambiar tu propio rol.');
        }

        if ($usuario->hasRole('admin')) {
            $usuario->removeRole('admin');
            $usuario->assignRole('cliente');
            $mensaje = "✅ {$usuario->name} ahora es Cliente.";
        } else {
            $usuario->removeRole('cliente');
            $usuario->assignRole('admin');
            $mensaje = "✅ {$usuario->name} ahora es Administrador.";
        }

        return redirect()->route('admin.usuarios')->with('success', $mensaje);
    }

    /**
     * Bitácora de auditoría con datos de usuario (rol y país).
     */
    public function indexBitacora()
    {
        $logs = \App\Models\AuditoriaLog::with(['user.roles'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.bitacora', compact('logs'));
    }
}
