<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\RequestKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $isMe = User::find(Auth::user()->id);
        $requests = RequestKey::all();

        return view('home', compact('users', 'isMe', 'requests'));
    }
    
    public function profile()
    {
        $profile = User::find(Auth::user()->id);

        return view('profile.show', [
            'profile' => $profile
        ]);
    }

    public function edit()
    {
        $profile = User::find(Auth::user()->id);

        return view('profile.edit', [
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $new_profile = $request->validate([
            'username' => 'required|alpha_dash:ascii',
            'email' => 'required|email:rfc',
        ]);

        $profile = User::find(Auth::user()->id);

        $profile->update($new_profile);

        return redirect('/profile');
    }
}
