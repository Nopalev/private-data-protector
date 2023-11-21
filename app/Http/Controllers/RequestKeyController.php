<?php

namespace App\Http\Controllers;

use App\Models\RequestKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\RC4;

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
        $req = RequestKey::find($request->id);

        $req->update([
            'status' => 'accepted',
        ]);

        return redirect()->back()->with('success', 'Updated status request file.');
    }
}
