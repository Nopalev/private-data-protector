<?php

namespace App\Http\Controllers;

use App\Models\RequestKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\RC4;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\HiddenString\HiddenString;

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
        $req = User::find(Auth::user()->id);
        // dd($profile->id, $request);
        RequestKey::create([
            'user_id_owner' => $request->user_id_owner,
            'user_id_req' => $req->id,
            'file_id' => $request->file_id,
            'status' => 'waiting',
            'symmetricKey' => "0"
        ]);

        return redirect()->back()->with('success', 'File request has been sent to the owner.');   
    }
    
    public function update(Request $request) {
        $files = User::find(Auth::user()->id)->files;
        $req = RequestKey::find($request->id);
        $symmetricKey = KeyFactory::generateEncryptionKey();
        $asymmetricKey = KeyFactory::generateEncryptionKeyPair();
        $req->user_id_req = Auth::user()->id;
        $req->user_id_owner = $request->user_id_owner;
        $req->file_id = $request->file_id;
        $req->status = 'accepted';
        KeyFactory::save($asymmetricKey, storage_path('app/public/keys/' . $request->user_id_owner . '.key'));
        // $symmetricKey = KeyFactory::loadEncryptionKey('app/public/keys/' . $request->user_id_owner . '.key');
        $symmetricText = \ParagonIE\Halite\Symmetric\Crypto ::encrypt(new HiddenString($files), $symmetricKey);
        
        $alice_keypair = \ParagonIE\Halite\KeyFactory::generateEncryptionKeyPair();
        $alice_secret = $alice_keypair->getSecretKey();
        $alice_public = $alice_keypair->getPublicKey();
        $send_to_bob = sodium_bin2hex($alice_public->getRawKeyMaterial());

        // dd($symmetricKey, $asymmetricKey);
        
        $req->update([
            'status' => 'accepted',
            'symmetricKey' => $symmetricKey,
            'keypair' => $send_to_bob
        ]);
        
        return redirect()->back()->with('success', $symmetricKey);
    }
}
