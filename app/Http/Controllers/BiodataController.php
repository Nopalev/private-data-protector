<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BiodataController extends Controller
{
    public function index()
    {
        $biodata = User::find(Auth::user()->id)->biodata;
        if (!$biodata) {
            return view('biodata.form');
        }

        return view('biodata.password');
    }

    public function show(Request $request)
    {
        if (Hash::check($request->password, Auth::user()->password)) {
            $decryptor = new EncryptionController;
            $biodata = User::find(Auth::user()->id)->biodata;

            $biodata->name = $decryptor->decrypt($request->password, $biodata->name);
            $biodata->gender = $decryptor->decrypt($request->password, $biodata->gender);
            $biodata->nationality = $decryptor->decrypt($request->password, $biodata->nationality);
            $biodata->religion = $decryptor->decrypt($request->password, $biodata->religion);
            $biodata->marital_status = $decryptor->decrypt($request->password, $biodata->marital_status);

            return view('biodata.show', [
                'biodata' => $biodata
            ]);
        }
        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }

    public function create(Request $request)
    {
        $encryptor = new EncryptionController;
        $request->validate([
            'name' => 'required',
            'nationality' => 'required',
        ]);

        $biodata = [
            'user_id' => Auth::user()->id,
            'name' => $encryptor->encrypt($request->password, $request->name),
            'gender' => $encryptor->encrypt($request->password, $request->gender),
            'nationality' => $encryptor->encrypt($request->password, $request->nationality),
            'religion' => $encryptor->encrypt($request->password, $request->religion),
            'marital_status' => $encryptor->encrypt($request->password, $request->marital_status),
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
        if (Hash::check($request->password, Auth::user()->password)) {
            $encryptor = new EncryptionController;
            $request->validate([
                'name' => 'required',
                'nationality' => 'required',
            ]);

            $biodata = User::find(Auth::user()->id)->biodata;

            $new_biodata = [
                'name' => $encryptor->encrypt($request->password, $request->name),
                'gender' => $encryptor->encrypt($request->password, $request->gender),
                'nationality' => $encryptor->encrypt($request->password, $request->nationality),
                'religion' => $encryptor->encrypt($request->password, $request->religion),
                'marital_status' => $encryptor->encrypt($request->password, $request->marital_status),
            ];

            $biodata->update($new_biodata);

            return redirect('home');
        }
        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }
}
