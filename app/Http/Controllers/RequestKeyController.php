<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\RequestKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ParagonIE\Halite\Asymmetric\EncryptionPublicKey;
use ParagonIE\Halite\Asymmetric\EncryptionSecretKey;
use phpseclib3\Crypt\RC4;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\HiddenString\HiddenString;
use Throwable;

class RequestKeyController extends Controller
{
    public function index()
    {
        $files = User::find(Auth::user()->id)->files;
        $decryptor = new RC4;
        for ($i = 0; $i < count($files); $i++) {
            $decryptor->setKey($files[$i]->enc_key);
            $files[$i]->filename = $decryptor->decrypt($files[$i]->filename);
        }

        $requests = RequestKey::where('user_id_owner', Auth::user()->id)->get();
        
        return view('request.index', compact('requests', 'files'));
    }
    
    public function create(Request $request)
    {
        $usr_req = User::find(Auth::user()->id);
        $checkRow = RequestKey::where('user_id_owner', '=', $request->user_id_owner, 'and')
            ->where('user_id_req', '=', $usr_req->id, 'and')
            ->where('file_id', '=', $request->file_id)
            ->first();
        if ($checkRow){
            $checkRow->update([
                'status' => 'waiting',
                'symmetricKey' => "0",
                'asymmetricKey' => "0"
            ]);
        }
        else {
            RequestKey::create([
                'user_id_owner' => $request->user_id_owner,
                'user_id_req' => $usr_req->id,
                'file_id' => $request->file_id,
                'status' => 'waiting',
                'symmetricKey' => "0",
                'asymmetricKey' => "0"
            ]);
        }

        return redirect()->back()->with('success', 'File request has been sent to the owner.');   
    }
    
    public function update(Request $request) {
        $req = RequestKey::find($request->id);
        $symmetricKey = KeyFactory::generateEncryptionKey();
        $hex_symmetric = KeyFactory::export($symmetricKey)->getString();

        $public_key = $req->user_owner->userKey->public_key;
        $private_key = Storage::get('keys/' . $req->user_owner->username . '.key');

        $hex_public = new HiddenString(sodium_hex2bin($public_key));
        $hex_private = new HiddenString(sodium_hex2bin($private_key));

        $public_key = new EncryptionPublicKey($hex_public);
        $private_key = new EncryptionSecretKey($hex_private);

        $encrypted = \ParagonIE\Halite\Asymmetric\Crypto::encrypt(
            new HiddenString(
                $hex_symmetric
            ),
            $private_key,
            $public_key,
        );

        $req->update([
            'status' => 'accepted',
            'symmetricKey' => $hex_symmetric,
            'asymmetricKey' => $encrypted
        ]);
        
        return redirect()->back()->with('success', $encrypted);
    }

    public function form(String $id){
        $req = RequestKey::find($id);
        return view('request.form', compact('req'));
    }

    public function download(Request $request){
        $req = RequestKey::find($request->id);
        $symkey = $req->symmetricKey;

        $public_key = $req->user_owner->userKey->public_key;
        $private_key = Storage::get('keys/' . $req->user_owner->username . '.key');

        $hex_public = new HiddenString(sodium_hex2bin($public_key));
        $hex_private = new HiddenString(sodium_hex2bin($private_key));

        $public_key = new EncryptionPublicKey($hex_public);
        $private_key = new EncryptionSecretKey($hex_private);

        try {
            $decrypted = \ParagonIE\Halite\Asymmetric\Crypto::decrypt(
                $request->key,
                $private_key,
                $public_key
            )->getString();
        } 
        catch (Throwable $e) {
            return redirect()->back()->with('alert', 'Key is incorrect!');
        }

        if($decrypted == $symkey){
            if (!Storage::exists('public/temp')) {
                Storage::makeDirectory('public/temp');
            }
            $file = File::find($req->file_id);
            $decryptor = new RC4;
            $decryptor->setKey($file->enc_key);
    
            $file->filename = $decryptor->decrypt($file->filename);
    
            $file_src = fopen(public_path('storage/' . $file->filetype . 's/' . $file->filecode), 'r');
            $raw = fread($file_src, filesize(public_path('storage/' . $file->filetype . 's/' . $file->filecode)));
            fclose($file_src);
    
            $file_dest = fopen(public_path('storage/temp/' . $file->filename), 'w+');
            fwrite($file_dest, $decryptor->decrypt($raw));
            fclose($file_dest);
            return response()->download(public_path('storage/temp/' . $file->filename));
        }
        else {
            return redirect()->back()->with('alert', 'Key is incorrect!');
        }
    }
}
