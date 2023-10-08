<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(){
        $files = User::find(Auth::user()->id)->files;
        if($files->isEmpty()){
            return view('home')->with('status', 'You have not uploaded any files yet!');
        }
        return view('home', [
            'files' => $files
        ]);
    }

    public function form(){
        return view('file.form');
    }

    public function create(Request $request){
        if(Hash::check($request->password, Auth::user()->password)){
            $request->validate([
                'file' => 'required|mimes:pdf,docx,xls,xlsx,jpg,jpeg,png,mp4'
            ]);
    
            $time = Carbon::now();
            $filename = $time->toDateString() . '_' . $time->toTimeString() . '_' . $request->file->getClientOriginalName();
            $filetype = '';
            $extension = $request->file->getClientOriginalExtension();
    
            if($extension === 'pdf' || $extension === 'docx' || $extension === 'xls' || $extension === 'xlsx'){
                $filetype = 'document';
            }
            else if($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png'){
                $filetype = 'image';
            }
            else{
                $filetype = 'video';
            }
    
            $request->file->storeAs('public/' . $filetype . 's', $filename);
            
            File::create([
                'user_id' => Auth::user()->id,
                'filename' => $filename,
                'filetype' => $filetype
            ]);
            return redirect('home');
        }
        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }

    public function password_confirmation(String $id){
        return view('file.password', [
            'file_id' => $id
        ]);
    }
    
    public function show(Request $request, String $id){
        if(Hash::check($request->password, Auth::user()->password)){
            $file = File::find($id);
            return view('file.show', [
                'file' => $file
            ]);
        }
        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }

    public function download(String $id){
        $file = File::find($id);
        return response()->download(public_path('storage/' . $file->filetype . 's/' . $file->filename));
    }

    public function destroy(String $id){
        $file = File::find($id);
        $file->delete();
        Storage::delete('public/' . $file->filetype . 's/'. $file->filename);
        return redirect('home')->with('status', 'File ' . $file->filename . ' has been deleted.');
    }
}
