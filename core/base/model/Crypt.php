<?php

namespace core\base\model;

use core\base\controllers\Singletone;

class Crypt
{

    use Singletone;

    private $cryptMethod = 'AES-128-CBC';
    private $hasheAlgoritm = 'sha256';
    private $hasheLength = 32;


    public function encrypt($str)
    {

        $ivlen = openssl_cipher_iv_length($this->cryptMethod);

        $iv = openssl_random_pseudo_bytes($ivlen);

        $cipherText = openssl_encrypt($str, $this->cryptMethod, CRYPT_KEY, OPENSSL_RAW_DATA, $iv);

        $hmac = hash_hmac($this->hasheAlgoritm, $cipherText, CRYPT_KEY, true);

//        return base64_encode($iv . $hmac . $cipherText);

        $cipherText_comb = '112233445566778899';
        $iv_comp = 'abcdefg';
        $hmac_comb = '00000000000000';

        $res = '1122a33b445c5667d78899efg  ';

    }

    public function decrypt($str)
    {

        $crypt_str = base64_decode($str);

        $ivlen = openssl_cipher_iv_length($this->cryptMethod);

        $iv = substr($crypt_str, 0, $ivlen);

        $hmac = substr($crypt_str, $ivlen, $this->hasheLength);

        $cipherText = substr($crypt_str, $ivlen + $this->hasheLength);

        $originalPlaintext = openssl_decrypt($cipherText, $this->cryptMethod, CRYPT_KEY, OPENSSL_RAW_DATA, $iv);

        $calcmac = hash_hmac($this->hasheAlgoritm, $cipherText, CRYPT_KEY, true);

        if (hash_equals($hmac, $calcmac)) return $originalPlaintext;

        return false;
    }

}