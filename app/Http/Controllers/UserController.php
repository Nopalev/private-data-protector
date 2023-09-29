<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        
    }

    public function show(Request $request){
        // dd($request);
        return $request->all();
    }
}
