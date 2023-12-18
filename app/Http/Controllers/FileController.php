<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use phpseclib3\Crypt\RC4;
use App\Services\CryptoService;
use App\Services\PdfService;


class FileController extends Controller
{
    protected $cryptoService;
    protected $pdfService;

    public function __construct(CryptoService $cryptoService, PdfService $pdfService)
    {
        $this->cryptoService = $cryptoService;
        $this->pdfService = $pdfService;
    }

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
        $new_key = Str::random(16);
        $encryptor = new RC4;
        $encryptor->setKey($new_key);

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

        $hash = Hash::make($request->file->get());

        $file_dest = fopen(public_path('storage/' . $filetype . 's/' . $filecode), 'w+');
        fwrite($file_dest, $encryptor->encrypt($request->file->get()));
        fclose($file_dest);

        // $signatureKeyPair = $this->encryptionController->generateKeyPair();
        // $signaturePrivateKey = $this->cryptoService->encrypt($user->password, $signatureKeyPair['privateKey']);
        // $this->encryptionController->generateSignature($user->id, $request->file->get(), $signaturePrivateKey);

        File::create([
            'user_id' => Auth::user()->id,
            'filename' => $filename,
            'enc_key' => $new_key,
            'filecode' => $filecode,
            'filetype' => $filetype,
            'mime' => $request->file->getClientMimeType(),
            'hash' => $hash
            //'signature_private_key' => $signaturePrivateKey
        ]);

        
        return redirect('home');
    }

    public function show(String $id)
    {

        $file = File::find($id);
        $decryptor = new RC4;
        $decryptor->setKey($file->enc_key);

        $file->filename = $decryptor->decrypt($file->filename);

        return view('file.show', [
            'file' => $file,
        ]);
    }

    public function download(String $id)
    {
        if (!Storage::exists('public/temp')) {
            Storage::makeDirectory('public/temp');
        }
        $file = File::find($id);
        $decryptor = new RC4;
        $decryptor->setKey($file->enc_key);

        $file->filename = $decryptor->decrypt($file->filename);

        $file_src = fopen(public_path('storage/' . $file->filetype . 's/' . $file->filecode), 'r');
        $raw = fread($file_src, filesize(public_path('storage/' . $file->filetype . 's/' . $file->filecode)));
        fclose($file_src);

        $file_dest = fopen(public_path('storage/temp/' . $file->filename), 'w+');
        fwrite($file_dest, $decryptor->decrypt($raw));
        fclose($file_dest);
        return response()->download(public_path('storage/temp/' . $file->filename));
    }

    public function destroy(String $id)
    {
        $file = File::find($id);
        Storage::delete('public/' . $file->filetype . 's/' . $file->filecode);
        $file->delete();
        return redirect('home')->with('status', 'The file has been deleted.');
    }
}
