<?php

namespace App\Encryptor;

class Encryptor
{
    public function derive_key(String $password){
        $password .= $password;
        $password = substr($password, 0, 16);
        $password = substr($password, 1) . substr($password, 0, 1);
        $appkey = explode(':', config('app.key'))[1];
        dd($password, $appkey);
    }
}