<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'alpha_dash:ascii'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('home');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function create(Request $request)
    {

        $user = $request->validate([
            'username' => 'required|alpha_dash:ascii',
            'email' => 'required|email:rfc',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        User::create($user);

        if (Auth::attempt($user)) {
            $request->session()->regenerate();
        }

        return redirect()->intended('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function edit()
    {
        return view('auth.passwords.change');
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);
        
        if (Hash::check($request->old_password, $user->password)) {
            $encryptor = new EncryptionController;
            $encryptor->changePassword($request->old_password, $request->password);
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/');
        }
        return back()->withErrors([
            'old_password' => 'The provided credentials do not match our records.',
        ])->onlyInput('old_password');
    }
}
