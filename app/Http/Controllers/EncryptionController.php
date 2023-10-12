<?php

namespace App\Http\Controllers;

use App\Models\PublicKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\DES;
use phpseclib3\Crypt\RC4;

class EncryptionController extends Controller
{
    private function getPublicKey(String $type){
        $appkey = PublicKey::all()->last();
        return $appkey[$type];
    }

    private function derive_key(String $password){
        $password .= $password;
        $password = substr($password, 0, 16);
        $appkey = $this->getPublicKey('public_key');
        for($i = 0; $i < 4; $i++){
            for($j = 0; $j < 16; $j++){
                $password[$j] =  $password[$j] ^ $appkey[$j];
            }
            $password = substr($password, 1) . substr($password, 0, 1);
        }
        dd($password, $appkey, strlen($password));
    }

    private function derive_IV(String $password){
        $password .= $password;
        $password = substr($password, strlen($password)-16, strlen($password));
        $appkey = $this->getPublicKey('public_IV');
        for($i = 0; $i < 4; $i++){
            for($j = 0; $j < 16; $j++){
                $password[$j] =  $password[$j] ^ $appkey[$j];
            }
            $password = substr($password, 1) . substr($password, 0, 1);
        }
        dd($password, $appkey, strlen($password));
    }

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

    public function encrypt(String $password, $text){
        $user = User::find(Auth::user()->id);
        if($user->encryption_method === 'AES'){
            $aes = new AES(strtolower($user->encryption_mode));
            $aes->setKey($this->derive_key($password));
            if($aes->usesIV()){
                $aes->setIV($this->derive_IV($password));
            }
            return $aes->encrypt($text);
        }
        elseif($user->encryption_method === 'DES'){
            $des = new DES(strtolower($user->encryption_mode));
            $des->setKey($this->derive_key($password));
            if($des->usesIV()){
                $des->setIV($this->derive_IV($password));
            }
            return $des->encrypt($text);
        }
        elseif($user->encryption_method === 'RC4'){
            $rc4 = new RC4(strtolower($user->encryption_mode));
            $rc4->setKey($this->derive_key($password));
            return $rc4->encrypt($text);
        }
    }

    public function decrypt(String $password, $text){
        $user = User::find(Auth::user()->id);
        if($user->encryption_method === 'AES'){
            $aes = new AES(strtolower($user->encryption_mode));
            $aes->setKey($this->derive_key($password));
            if($aes->usesIV()){
                $aes->setIV($this->derive_IV($password));
            }
            return $aes->decrypt($text);
        }
        elseif($user->encryption_method === 'DES'){
            $des = new DES(strtolower($user->encryption_mode));
            $des->setKey($this->derive_key($password));
            if($des->usesIV()){
                $des->setIV($this->derive_IV($password));
            }
            return $des->decrypt($text);
        }
        elseif($user->encryption_method === 'RC4'){
            $rc4 = new RC4(strtolower($user->encryption_mode));
            $rc4->setKey($this->derive_key($password));
            return $rc4->decrypt($text);
        }
    }
}
