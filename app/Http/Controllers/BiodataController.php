<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\RC4;

class BiodataController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $biodata = $user->biodata;
        if (!$biodata) {
            return view('biodata.form');
        }

        $decryptor = new RC4();
        $decryptor->setKey($user->userKey->user_key);

        $biodata->name = $decryptor->decrypt($biodata->name);
        $biodata->gender = $decryptor->decrypt($biodata->gender);
        $biodata->nationality = $decryptor->decrypt($biodata->nationality);
        $biodata->religion = $decryptor->decrypt($biodata->religion);
        $biodata->marital_status = $decryptor->decrypt($biodata->marital_status);

        return view('biodata.show', [
            'biodata' => $biodata
        ]);
    }

    public function create(Request $request)
    {
        $encryptor = new RC4();
        $encryptor->setKey(Auth::user()->userKey->user_key);

        $request->validate([
            'name' => 'required',
            'nationality' => 'required',
        ]);

        $biodata = [
            'user_id' => Auth::user()->id,
            'name' => $encryptor->encrypt($request->name),
            'gender' => $encryptor->encrypt($request->gender),
            'nationality' => $encryptor->encrypt($request->nationality),
            'religion' => $encryptor->encrypt($request->religion),
            'marital_status' => $encryptor->encrypt($request->marital_status),
        ];

        $biodata = Biodata::create($biodata);

        return redirect('home');
    }

    public function edit()
    {
        $genders = ['Male', 'Female', 'Non-Binary'];
        $religions = ['Islam', 'Christian', 'Protestant', 'Hindu', 'Buddha', 'Konghucu'];
        $marital_statuses = ['Single', 'Married', 'Divorced', 'Widowed'];

        return view('biodata.edit', [
            'genders' => $genders,
            'religions' => $religions,
            'marital_statuses' => $marital_statuses
        ]);
    }

    public function update(Request $request)
    {
        $encryptor = new RC4();
        $encryptor->setKey(Auth::user()->userKey->user_key);

        $request->validate([
            'name' => 'required',
            'nationality' => 'required',
        ]);

        $biodata = User::find(Auth::user()->id)->biodata;

        $new_biodata = [
            'name' => $encryptor->encrypt($request->name),
            'gender' => $encryptor->encrypt($request->gender),
            'nationality' => $encryptor->encrypt($request->nationality),
            'religion' => $encryptor->encrypt($request->religion),
            'marital_status' => $encryptor->encrypt($request->marital_status),
        ];

        $biodata->update($new_biodata);

        return redirect('home');
    }
}
