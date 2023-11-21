<?php

namespace App\Http\Controllers;

use App\Models\RequestKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestKeyController extends Controller
{
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
}
