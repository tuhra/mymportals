<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Crypt;

class AesEncDec 
{
    public function AesDecrypt($plaintext) {
        $encryptedString = Crypt::encryptString($plaintext);
        return $encryptedString;
    }

    public function AesDecrypt($encrypted) {
        $plaintext = Crypt::decryptString($encrypted);
        return $plaintext;
    }
}