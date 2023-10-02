<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function show(Request $request){
        $file = File::find($request->file_id);
        return view('file.show', [
            'file' => $file
        ]);
    }

    public function download(Request $request){
        $file = File::find($request->file_id);
        $header = array(
            'Content-Type: application/pdf',
          );
        return response()->download(public_path('storage/' . $file->filetype . 's/' . $file->filename));
    }

    public function destroy(Request $request){
        $file = File::find($request->file_id);
        $file->delete();
        return redirect('home')->with('status', 'File ' . $file->filename . ' has been deleted.');
    }
}
