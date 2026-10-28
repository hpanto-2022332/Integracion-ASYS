<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLogin(){
        return view('login');
    }

    public function showRegister(){
        return view('register');
    }

    public function register(Request $request){
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'pais'     => 'required|string|in:Guatemala,El Salvador', // 🛡️ Validación regional obligatoria
        ]);

        // Creación incorporando el país de origen seleccionado
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

    public function login(Request $request){
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.usuarios');
            }
            
            return redirect()->intended('dashboard');
        }
        return back()
            ->withErrors(['email' => 'Correo o contraseña incorrectos.'])
            ->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    public function indexUsuarios(){
        $usuarios = User::with('roles')->get();
        return view('admin.usuarios', compact('usuarios'));
    }

    //despliaga una bitacora de acciones
    public function indexBitacora()
    {
        // Recupera los logs más recientes con paginación limpia
        $logs = \App\Models\AuditoriaLog::orderBy('created_at', 'desc')->paginate(30);
        return view('admin.bitacora', compact('logs'));
    }

}
