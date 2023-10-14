<?php

namespace App\Http\Controllers;

use App\Events\FileDownloadProcessed;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    public function index()
    {
        $files = User::find(Auth::user()->id)->files;
        if ($files->isEmpty()) {
            return view('home')->with('status', 'You have not uploaded any files yet!');
        }
        return view('home', [
            'files' => $files
        ]);
    }

    public function form()
    {
        return view('file.form');
    }

    public function create(Request $request)
    {
        if (Hash::check($request->password, Auth::user()->password)) {
            $encryptor = new EncryptionController;
            $request->validate([
                'file' => 'required|mimes:pdf,docx,xls,xlsx,jpg,jpeg,png,mp4'
            ]);

            $timestamp = time();
            $extension = $request->file->getClientOriginalExtension();
            $date_string = date('Y-m-d_H-i-s', $timestamp);
            $filecode = Auth::user()->id . '_' . $date_string . '.' . $extension;
            $filename = $date_string. '_' . $request->file->getClientOriginalName();
            $filename = $encryptor->encrypt($request->password, $filename);
            $filetype = '';

            if ($extension === 'pdf' || $extension === 'docx' || $extension === 'xls' || $extension === 'xlsx') {
                $filetype = 'document';
            } else if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png') {
                $filetype = 'image';
            } else {
                $filetype = 'video';
            }

            $file_dest = fopen(public_path('storage/' . $filetype . 's/' . $filecode), 'w+');
            fwrite($file_dest, $encryptor->encrypt($request->password, $request->file->get()));
            fclose($file_dest);

            File::create([
                'user_id' => Auth::user()->id,
                'filename' => $filename,
                'filecode' => $filecode,
                'filetype' => $filetype,
                'mime' => $request->file->getClientMimeType()
            ]);
            return redirect('home');
        }
        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }

    public function password_confirmation(String $id)
    {
        return view('file.password', [
            'file_id' => $id
        ]);
    }

    public function show(Request $request, String $id)
    {
        if (Hash::check($request->password, Auth::user()->password)) {
            $decryptor = new EncryptionController;
            $file = File::find($id);

            $file->filename = $decryptor->decrypt($request->password, $file->filename);

            return view('file.show', [
                'file' => $file,
            ]);
        }
        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }

    public function download(Request $request, String $id)
    {
        if (Hash::check($request->password, Auth::user()->password)) {
            if(!Storage::exists('public/temp')) {
                Storage::makeDirectory('public/temp');
            }
            $decryptor = new EncryptionController;
            $file = File::find($id);

            $file->filename = $decryptor->decrypt($request->password, $file->filename);

            $file_src = fopen(public_path('storage/' . $file->filetype . 's/' . $file->filecode), 'r');
            $raw = fread($file_src, filesize(public_path('storage/' . $file->filetype . 's/' . $file->filecode)));
            fclose($file_src);

            $file_dest = fopen(public_path('storage/temp/' . $file->filename), 'w+');
            fwrite($file_dest, $decryptor->decrypt($request->password, $raw));
            fclose($file_dest);
            return response()->download(public_path('storage/temp/' . $file->filename));
        }
        return redirect('home')->with('alert', 'The provided password did not match our records.');
    }

    public function destroy(String $id)
    {
        $file = File::find($id);
        Storage::delete('public/' . $file->filetype . 's/' . $file->filecode);
        $file->delete();
        return redirect('home')->with('status', 'The file has been deleted.');
    }
}