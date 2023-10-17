<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\DES;
use phpseclib3\Crypt\RC4;

class EncryptionController extends Controller
{
    private function getPublicKey(String $id, String $type)
    {
        $appkey = User::find($id)->publicKey;
        return $appkey[$type];
    }

    private function derive_key(String $id, String $password)
    {
        $password .= $password;
        $password = substr($password, 0, 16);
        $appkey = $this->getPublicKey($id, 'public_key');
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 16; $j++) {
                $password[$j] =  $password[$j] ^ $appkey[$j];
            }
            $password = substr($password, 1) . substr($password, 0, 1);
        }
        return $password;
    }

    private function derive_IV(String $id, String $password)
    {
        $password .= $password;
        $password = substr($password, strlen($password) - 16, strlen($password));
        $appkey = $this->getPublicKey($id, 'public_IV');
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 16; $j++) {
                $password[$j] =  $password[$j] ^ $appkey[$j];
            }
            $password = substr($password, 1) . substr($password, 0, 1);
        }
        return $password;
    }

    private function changeEncryptionMethod(
        String $old_method,
        String $new_method,
        String $old_mode,
        String $new_mode,
        String $password
        ) {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $old = $new = null;
        if ($old_method === 'AES') {
            $old = new AES(strtolower($old_mode));
            $old->setKey($this->derive_key($user_id, $password));
            if ($old->usesIV()) {
                $old->setIV($this->derive_IV($user_id, $password));
            }
        } elseif ($old_method === 'DES') {
            $old = new DES(strtolower($old_mode));
            $old->setKey(substr($this->derive_key($user_id, $password), 0, 8));
            if ($old->usesIV()) {
                $old->setIV(substr($this->derive_IV($user_id, $password), 0, 8));
            }
        } elseif ($old_method === 'RC4') {
            $old = new RC4(strtolower($old_mode));
            $old->setKey($this->derive_key($user_id, $password));
        }

        if ($new_method === 'AES') {
            $new = new AES(strtolower($new_mode));
            $new->setKey($this->derive_key($user_id, $password));
            if ($new->usesIV()) {
                $new->setIV($this->derive_IV($user_id, $password));
            }
        } elseif ($new_method === 'DES') {
            $new = new DES(strtolower($new_mode));
            $new->setKey(substr($this->derive_key($user_id, $password), 0, 8));
            if ($new->usesIV()) {
                $new->setIV(substr($this->derive_IV($user_id, $password), 0, 8));
            }
        } elseif ($new_method === 'RC4') {
            $new = new RC4(strtolower($new_mode));
            $new->setKey($this->derive_key($user_id, $password));
        }

        if (!is_null($user->biodata)) {
            $biodata = $user->biodata;

            $biodata->name = $old->decrypt($biodata->name);
            $biodata->gender = $old->decrypt($biodata->gender);
            $biodata->nationality = $old->decrypt($biodata->nationality);
            $biodata->religion = $old->decrypt($biodata->religion);
            $biodata->marital_status = $old->decrypt($biodata->marital_status);

            $biodata->name = $new->encrypt($biodata->name);
            $biodata->gender = $new->encrypt($biodata->gender);
            $biodata->nationality = $new->encrypt($biodata->nationality);
            $biodata->religion = $new->encrypt($biodata->religion);
            $biodata->marital_status = $new->encrypt($biodata->marital_status);

            $biodata->save();
        }
        if ($user->files->isNotEmpty()) {
            foreach ($user->files as $file) {
                $file->filename = $old->decrypt($file->filename);
                $file->filename = $new->encrypt($file->filename);
                $file->save();

                $file_src = fopen(public_path('storage/' . $file->filetype . 's/' . $file->filecode), 'r');
                $raw = fread($file_src, filesize(public_path('storage/' . $file->filetype . 's/' . $file->filecode)));
                fclose($file_src);

                $file_dest = fopen(public_path('storage/' . $file->filetype . 's/' . $file->filecode), 'w+');
                fwrite($file_dest, $new->encrypt($old->decrypt($raw)));
                fclose($file_dest);
            }
        }
    }

    public function index()
    {
        $encryption_method = [
            'AES',
            'DES',
            'RC4'
        ];
        $encryption_mode = [
            'CBC',
            'CFB',
            'OFB',
            'CTR'
        ];

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $set = 'false';
        if (!(is_null($user->encryption_method) && is_null($user->encryption_mode))) {
            $set = 'true';
        }

        return view('encryption.form', [
            'methods' => $encryption_method,
            'modes' => $encryption_mode,
            'set' => $set
        ]);
    }

    public function update(Request $request)
    {
        if($request->set == 'false'){
            $user = User::find(Auth::user()->id);
            $user->encryption_method = $request->method;
            $user->encryption_mode = $request->mode;
            $user->save();
            return redirect('home')->with('status', 'Encryption setting has been updated');
        }

        else if (Hash::check($request->password, Auth::user()->password)) {
            $user = User::find(Auth::user()->id);
            $this->changeEncryptionMethod($user->encryption_method, $request->method, $user->encryption_mode, $request->mode, $request->password);
            $user->encryption_method = $request->method;
            $user->encryption_mode = $request->mode;
            $user->save();
            return redirect('home')->with('status', 'Encryption setting has been updated');
        }

        return redirect()->back()->with('alert', 'The provided password did not match our records.');
    }

    public function encrypt(String $password, $text)
    {
        $user = User::find(Auth::user()->id);
        if ($user->encryption_method === 'AES') {
            $aes = new AES(strtolower($user->encryption_mode));
            $aes->setKey($this->derive_key($user->id, $password));
            if ($aes->usesIV()) {
                $aes->setIV($this->derive_IV($user->id, $password));
            }
            return $aes->encrypt($text);
        } elseif ($user->encryption_method === 'DES') {
            $des = new DES(strtolower($user->encryption_mode));
            $des->setKey(substr($this->derive_key($user->id, $password), 0, 8));
            if ($des->usesIV()) {
                $des->setIV(substr($this->derive_IV($user->id, $password), 0, 8));
            }
            return $des->encrypt($text);
        } elseif ($user->encryption_method === 'RC4') {
            $rc4 = new RC4(strtolower($user->encryption_mode));
            $rc4->setKey($this->derive_key($user->id, $password));
            return $rc4->encrypt($text);
        }
    }

    public function decrypt(String $password, $text)
    {
        $user = User::find(Auth::user()->id);
        if ($user->encryption_method === 'AES') {
            $aes = new AES(strtolower($user->encryption_mode));
            $aes->setKey($this->derive_key($user->id, $password));
            if ($aes->usesIV()) {
                $aes->setIV($this->derive_IV($user->id, $password));
            }
            return $aes->decrypt($text);
        } elseif ($user->encryption_method === 'DES') {
            $des = new DES(strtolower($user->encryption_mode));
            $des->setKey(substr($this->derive_key($user->id, $password), 0, 8));
            if ($des->usesIV()) {
                $des->setIV(substr($this->derive_IV($user->id, $password), 0, 8));
            }
            return $des->decrypt($text);
        } elseif ($user->encryption_method === 'RC4') {
            $rc4 = new RC4(strtolower($user->encryption_mode));
            $rc4->setKey($this->derive_key($user->id, $password));
            return $rc4->decrypt($text);
        }
    }

    public function changePassword(String $old_password, String $new_password)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        if (!is_null($user->biodata)) {
            $biodata = $user->biodata;

            $biodata->name = $this->decrypt($old_password, $biodata->name);
            $biodata->gender = $this->decrypt($old_password, $biodata->gender);
            $biodata->nationality = $this->decrypt($old_password, $biodata->nationality);
            $biodata->religion = $this->decrypt($old_password, $biodata->religion);
            $biodata->marital_status = $this->decrypt($old_password, $biodata->marital_status);

            $biodata->name = $this->encrypt($new_password, $biodata->name);
            $biodata->gender = $this->encrypt($new_password, $biodata->gender);
            $biodata->nationality = $this->encrypt($new_password, $biodata->nationality);
            $biodata->religion = $this->encrypt($new_password, $biodata->religion);
            $biodata->marital_status = $this->encrypt($new_password, $biodata->marital_status);

            $biodata->save();
        }
        if ($user->files->isNotEmpty()) {
            foreach ($user->files as $file) {
                $file->filename = $this->decrypt($old_password, $file->filename);
                $file->filename = $this->encrypt($new_password, $file->filename);
                $file->save();

                $file_src = fopen(public_path('storage/' . $file->filetype . 's/' . $file->filecode), 'r');
                $raw = fread($file_src, filesize(public_path('storage/' . $file->filetype . 's/' . $file->filecode)));
                fclose($file_src);

                $file_dest = fopen(public_path('storage/' . $file->filetype . 's/' . $file->filecode), 'w+');
                fwrite($file_dest, $this->encrypt($new_password, $this->decrypt($old_password, $raw)));
                fclose($file_dest);
            }
        }
    }

    public function factory_encrypt(User $user, String $password, $text)
    {
        $encryption_method = $user->encryption_method;
        $encryption_mode = $user->encryption_mode;

        if ($encryption_method === 'AES') {
            $aes = new AES(strtolower($encryption_mode));
            $aes->setKey($this->derive_key($user->id, $password));
            if ($aes->usesIV()) {
                $aes->setIV($this->derive_IV($user->id, $password));
            }
            return $aes->encrypt($text);
        } elseif ($encryption_method === 'DES') {
            $des = new DES(strtolower($encryption_mode));
            $des->setKey(substr($this->derive_key($user->id, $password), 0, 8));
            if ($des->usesIV()) {
                $des->setIV(substr($this->derive_IV($user->id, $password), 0, 8));
            }
            return $des->encrypt($text);
        } elseif ($encryption_method === 'RC4') {
            $rc4 = new RC4(strtolower($encryption_mode));
            $rc4->setKey($this->derive_key($user->id, $password));
            return $rc4->encrypt($text);
        }
    }

    public function factory_decrypt(User $user, String $password, $text)
    {
        $encryption_method = $user->encryption_method;
        $encryption_mode = $user->encryption_mode;

        if ($encryption_method === 'AES') {
            $aes = new AES(strtolower($encryption_mode));
            $aes->setKey($this->derive_key($user->id, $password));
            if ($aes->usesIV()) {
                $aes->setIV($this->derive_IV($user->id, $password));
            }
            return $aes->decrypt($text);
        } elseif ($encryption_method === 'DES') {
            $des = new DES(strtolower($encryption_mode));
            $des->setKey(substr($this->derive_key($user->id, $password), 0, 8));
            if ($des->usesIV()) {
                $des->setIV(substr($this->derive_IV($user->id, $password), 0, 8));
            }
            return $des->decrypt($text);
        } elseif ($encryption_method === 'RC4') {
            $rc4 = new RC4(strtolower($encryption_mode));
            $rc4->setKey($this->derive_key($user->id, $password));
            return $rc4->decrypt($text);
        }
    }

    public function entropy(String $text)
    {
        $totalChars = strlen($text);
        $entropy = 0;
        $charCount = [];

        // Count the frequency of each character
        for ($i = 0; $i < $totalChars; $i++) {
            $char = $text[$i];
            if (!isset($charCount[$char])) {
                $charCount[$char] = 0;
            }
            $charCount[$char]++;
        }

        // Calculate the entropy
        foreach ($charCount as $char => $count) {
            $probability = $count / $totalChars;
            $entropy -= $probability * log($probability, 2);
        }
        return $entropy;
    }
}
