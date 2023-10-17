<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EncryptionController;
use App\Models\User;
use Illuminate\Http\Request;

class  DatasetController extends Controller
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
        
        $timestamp_start = microtime(true);
        $encryption_start = date('Y-m-d_H-i-s.u', $timestamp_start);
        
        $biodata->name = $decryptor->factory_decrypt($user, $password, $biodata->name);
        $biodata->gender = $decryptor->factory_decrypt($user, $password, $biodata->gender);
        $biodata->nationality = $decryptor->factory_decrypt($user, $password, $biodata->nationality);
        $biodata->religion = $decryptor->factory_decrypt($user, $password, $biodata->religion);
        $biodata->marital_status = $decryptor->factory_decrypt($user, $password, $biodata->marital_status);
        
        $timestamp_end = microtime(true);
        $encryption_end = date('Y-m-d_H-i-s.u', $timestamp_end);
        
        $duration = round(($timestamp_end - $timestamp_start)*1000);
        
        $response = [
            'user' => $user,
            'start' => $encryption_start,
            'end' => $encryption_end,
            'duration' => $duration
        ];

        return $response;
    }

    public function files(Request $request, String $id){
        $decryptor = new EncryptionController;
        $password = $request->header("php-auth-pw");
        $user = User::find($id);
        $files = $user->files;
        
        $timestamp_start = microtime(true);
        $encryption_start = date('Y-m-d_H-i-s.u', $timestamp_start);

        foreach($files as $file){
            $file->filename = $decryptor->factory_decrypt($user, $password, $file->filename);

            $file_src = fopen(public_path('storage/' . $file->filetype . 's/' . $file->filecode), 'r');
            $raw = fread($file_src, filesize(public_path('storage/' . $file->filetype . 's/' . $file->filecode)));
            fclose($file_src);
            $decrypted = $decryptor->factory_decrypt($user, $password, $raw);
        }

        $timestamp_end = microtime(true);
        $encryption_end = date('Y-m-d_H-i-s.u', $timestamp_end);

        $duration = round(($timestamp_end - $timestamp_start)*1000);

        $response = [
            'user' => $user,
            'start' => $encryption_start,
            'end' => $encryption_end,
            'duration' => $duration
        ];

        return $response;
    }
}
