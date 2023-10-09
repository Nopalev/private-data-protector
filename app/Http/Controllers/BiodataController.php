<?php

namespace App\Http\Controllers;

use App\Encryptor\Encryptor;
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

    public function show(Request $request){
        if(Hash::check($request->password, Auth::user()->password)){
            $encryptor = new Encryptor;
            $key = $encryptor->derive_key($request->password);
            $biodata = User::find(Auth::user()->id)->biodata;
            return view('biodata.show', [
                'biodata' => $biodata
            ]);
        }
        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nationality' => 'required',
        ]);

        $biodata = [
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'religion' => $request->religion,
            'marital_status' => $request->marital_status,
        ];

        $biodata = Biodata::create($biodata);

        return view('biodata.show', [
            'biodata' => $biodata
        ]);
    }

    public function edit()
    {
        $biodata = User::find(Auth::user()->id)->biodata;

        $genders = [ 'Male', 'Female', 'Non-Binary'];
        $religions = ['Islam', 'Christian', 'Protestant', 'Hindu', 'Buddha', 'Konghucu'];
        $marital_statuses = ['Single', 'Married', 'Divorced', 'Widowed'];

        return view('biodata.edit', [
            'biodata' => $biodata,
            'genders' => $genders,
            'religions' => $religions,
            'marital_statuses' => $marital_statuses
        ]);
    }

    public function update(Request $request)
    {
        if(Hash::check($request->password, Auth::user()->password)){
            $request->validate([
                'name' => 'required',
                'nationality' => 'required',
            ]);
    
            $biodata = User::find(Auth::user()->id)->biodata;
    
            $new_biodata = [
                'name' => $request->name,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'religion' => $request->religion,
                'marital_status' => $request->marital_status,
            ];
    
            $biodata->update($new_biodata);
    
            return view('biodata.show', [
                'biodata' => $biodata
            ]);
        }
        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }
}
