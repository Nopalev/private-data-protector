<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EncryptionController;
use App\Models\User;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index(){
        $users = User::all();
        return $users;
    }
    
    public function biodata(Request $request, String $id){
        $decryptor = new EncryptionController;
        $password = $request->header("php-auth-pw");
        $user = User::find($id);
        $biodata = $user->biodata;
    
        $timestamp_start = time();
        $encryption_start = date('Y-m-d_H-i-s.u', $timestamp_start);

        $entropyb = $decryptor->entropy($biodata->name);
        $entropyp = $decryptor->entropy($password);
        
        $biodata->name = $decryptor->factory_decrypt($user->encryption_method, $user->encryption_mode, $password, $biodata->name);
        $biodata->gender = $decryptor->factory_decrypt($user->encryption_method, $user->encryption_mode, $password, $biodata->gender);
        $biodata->nationality = $decryptor->factory_decrypt($user->encryption_method, $user->encryption_mode, $password, $biodata->nationality);
        $biodata->religion = $decryptor->factory_decrypt($user->encryption_method, $user->encryption_mode, $password, $biodata->religion);
        $biodata->marital_status = $decryptor->factory_decrypt($user->encryption_method, $user->encryption_mode, $password, $biodata->marital_status);
        
        $timestamp_end = time();
        $encryption_end = date('Y-m-d_H-i-s.u', $timestamp_end);
        
        $duration = $timestamp_end - $timestamp_start;

        $response = [
            'user' => $user,
            'start' => $encryption_start,
            'end' => $encryption_end,
            'duration' => $duration,
            // 'entropyb' => $entropyb
        ];

        return $response;
    }

    public function files(Request $request, String $id){
        $decryptor = new EncryptionController;
        $password = $request->header("php-auth-pw");
        $user = User::find($id);
        $files = $user->files;
        
        
        $timestamp_start = time();
        $encryption_start = date('Y-m-d_H-i-s.u', $timestamp_start);

        foreach($files as $file){
            $file->filename = $decryptor->factory_decrypt($user->encryption_method, $user->encryption_mode, $password, $file->filename);

            $file_src = fopen(public_path('storage/' . $file->filetype . 's/' . $file->filecode), 'r');
            $raw = fread($file_src, filesize(public_path('storage/' . $file->filetype . 's/' . $file->filecode)));
            fclose($file_src);
            $ent = $decryptor->entropy($raw);
            $decrypted = $decryptor->factory_decrypt($user->encryption_method, $user->encryption_mode, $password, $raw);
        }

        $timestamp_end = time();
        $encryption_end = date('Y-m-d_H-i-s.u', $timestamp_end);

        $duration = $timestamp_end - $timestamp_start;

        $response = [
            'user' => $user,
            'start' => $encryption_start,
            'end' => $encryption_end,
            'duration' => $duration,
            'entropy' => $ent
        ];

        return $response;
    }
}
