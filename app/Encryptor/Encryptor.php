<?php

namespace App\Encryptor;

use App\Models\PublicKey;

class Encryptor
{
    private function getPublicKey(String $type){
        $appkey = PublicKey::all()->last();
        return $appkey[$type];
    }

    public function derive_key(String $password){
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

    public function derive_IV(String $password){
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
}