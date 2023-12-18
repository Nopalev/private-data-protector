<?php

namespace App\Services;

use phpseclib3\Crypt\RC4;

class CryptoService
{
    public function signData($data, $privateKey)
    {
        $privateKeyResource = openssl_pkey_get_private($privateKey); // Create a private key resource from the key string

        openssl_sign($data, $signature, $privateKeyResource); // Sign the data using the private key

        openssl_free_key($privateKeyResource); // Free the private key resource

        return base64_encode($signature); // Return the base64-encoded signature
    }

    public function verifySignature($data, $signature, $publicKey)
    {
        $publicKeyResource = openssl_pkey_get_public($publicKey); // Create a public key resource from the key string

        $isSignatureValid = openssl_verify($data, base64_decode($signature), $publicKeyResource) === 1; // Verify the signature using the public key

        openssl_free_key($publicKeyResource); // Free the public key resource

        return $isSignatureValid;
    }
}
