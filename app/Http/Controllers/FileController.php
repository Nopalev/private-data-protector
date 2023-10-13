<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            $encryptor = new EncryptionController;
            $request->validate([
                'file' => 'required|mimes:pdf,docx,xls,xlsx,jpg,jpeg,png,mp4'
            ]);
    
            $timestamp = time();
            $dateString = date('Y-m-d_H-i-s', $timestamp);
            $filename = $dateString. '_' . $request->file->getClientOriginalName();
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

            // $raw = $request->file->get();

            // $encrypted_file = new UploadedFile(
            //     $encryptor->encrypt($request->password, $raw),
            //     $request->file->getFilename(),
            //     $request->file->getMimeType(),
            //     0,
            //     true // Mark it as test, since the file isn't from real HTTP POST.
            // );

            // dd($request->file);
    
            $request->file->storeAs('public/' . $filetype . 's', $filename);
            
            File::create([
                'user_id' => Auth::user()->id,
                'filename' => $filename,
                'filetype' => $filetype,
                'mime' => $request->file->getClientMimeType()
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
                'file' => $file,
            ]);
        }
        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }

    public function download(Request $request, String $id){
        if(Hash::check($request->password, Auth::user()->password)){
            $file = File::find($id);
            return response()->download(public_path('storage/' . $file->filetype . 's/' . $file->filename));
        }
        return redirect('home')->with('alert', 'The provided password did not match our records.');
    }

    public function destroy(String $id){
        $file = File::find($id);
        $file->delete();
        Storage::delete('public/' . $file->filetype . 's/'. $file->filename);
        return redirect('home')->with('status', 'File ' . $file->filename . ' has been deleted.');
    }
}