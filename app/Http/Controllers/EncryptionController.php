<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncryptionController extends Controller
{
    public function index(){
        $encryption_method = [
            'AES',
            'DES',
            'RC4'
        ];
        $encryption_mode = [
            'CBC',
            'CFB',
            'OFB',
            'CTR'
        ];

        return view('encryption.form', [
            'methods' => $encryption_method,
            'modes' => $encryption_mode
        ]);
    }

    public function update(Request $request){
        $user = User::find(Auth::user()->id);
        $user->encryption_method = $request->method;
        $user->encryption_mode = $request->mode;
        $user->save();
        return redirect('home')->with('status', 'Encryption setting has been updated');
    }
}
