<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\DES;
use phpseclib3\Crypt\RC4;
use App\Services\CryptoService;

class EncryptionController extends Controller
{
    public function index()
    {
        return view('encryption.form');
    }

    public function update()
    {
        $user = User::find(Auth::user()->id);
        $old_key = $user->userKey->user_key;
        $new_key = Str::random(16);
        
        $biodata = $user->biodata;
        $encryptor = new RC4();
        $encryptor->setKey($new_key);
        $decryptor = new RC4();
        $decryptor->setKey($old_key);

        $biodata->name = $decryptor->decrypt($biodata->name);
        $biodata->gender = $decryptor->decrypt($biodata->gender);
        $biodata->nationality = $decryptor->decrypt($biodata->nationality);
        $biodata->religion = $decryptor->decrypt($biodata->religion);
        $biodata->marital_status = $decryptor->decrypt($biodata->marital_status);
        

        $biodata->name = $encryptor->encrypt($biodata->name);
        $biodata->gender = $encryptor->encrypt($biodata->gender);
        $biodata->nationality = $encryptor->encrypt($biodata->nationality);
        $biodata->religion = $encryptor->encrypt($biodata->religion);
        $biodata->marital_status = $encryptor->encrypt($biodata->marital_status);
        
        $biodata->save();
        $user->userKey()->update([
            'user_key' => $new_key
        ]);

        return redirect('home')->with('status', 'Encryption key has been updated');
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

    public function generateSignature($userId, $documentContent, $privateKey)
    {
        // Hash the document content (excluding the signature)
        $documentHash = hash('sha256', $documentContent);

        // Sign the hash with the private key
        $signature = $this->cryptoService->signData($documentHash, $privateKey);

        // Embed the signature in the PDF content
        $documentWithSignature = $this->embedSignatureInPDF($documentContent, $signature);

        // Save or update the signed PDF
        $this->saveSignedPDF($userId, $documentWithSignature);
    }

    public function verifySignature($userId, $documentContent, $publicKey)
    {
        // Extract the signature from the PDF content
        $signature = $this->extractSignatureFromPDF($documentContent);

        // Hash the document content (excluding the signature)
        $documentHash = hash('sha256', $documentContent);

        // Verify the signature using the public key
        $isSignatureValid = $this->cryptoService->verifySignature($documentHash, $signature, $publicKey);

        return $isSignatureValid;
    }

    private function embedSignatureInPDF($documentContent, $signature)
    {
        $signaturePlaceholder = '<<<SIGNATURE>>>'; //replace placeholder string with signature
        $documentWithSignature = str_replace($signaturePlaceholder, $signature, $documentContent);

        return $documentWithSignature;
    }

    private function extractSignatureFromPDF($documentContent)
    {
        $signatureRegex = '/<<<SIGNATURE>>>(.*?)<<<\/SIGNATURE>>>/s'; //use regular expression to find the signature
        preg_match($signatureRegex, $documentContent, $matches);

        return isset($matches[1]) ? $matches[1] : null;
    }

    private function saveSignedPDF($userId, $documentWithSignature)
    {
        $filePath = storage_path("app/signed_pdfs/{$userId}_signed.pdf");
        file_put_contents($filePath, $documentWithSignature);


        File::where('user_id', $userId)->update(['signed_pdf_path' => $filePath]);
    }
}
