<?php

namespace core\base\model;

use core\base\controllers\Singletone;

class Crypt
{

    use Singletone;

    private $cryptMethod = 'AES-128-CBC';
    private $hasheAlgoritm = 'sha256';
    private $hasheLength = 32;

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

    public function encrypt($str)
    {

        $ivlen = openssl_cipher_iv_length($this->cryptMethod);

        $iv = openssl_random_pseudo_bytes($ivlen);

        $cipherText = openssl_encrypt($str, $this->cryptMethod, CRYPT_KEY, OPENSSL_RAW_DATA, $iv);

        $hmac = hash_hmac($this->hasheAlgoritm, $cipherText, CRYPT_KEY, true);

//        return base64_encode($iv . $hmac . $cipherText);

        $cipherText_comb = '1122334455667788';
        $iv_comp = 'abcdefghijklmnop';
        $hmac_comb = '00000000000000000000000000000000';

        $res = $this->cryptCombine($cipherText_comb, $iv_comp, $hmac_comb);

        $crypt_data = $this->cryptUnCombine($res, $ivlen);

    }

    protected function cryptCombine($str, $iv, $hmac)
    {

        $new_str = '';

        $str_len = (strlen($str));

        $counter = (int)ceil(strlen(CRYPT_KEY) / $str_len + $this->hasheLength);

        $progress = 1;

        if ($counter >= $str_len) $counter = 1;

        for ($i = 0; $i < $str_len; $i++) {

            if ($counter < $str_len) {

                if ($counter === $i) {

                    $new_str .= substr($iv, $progress - 1, 1);
                    $progress++;
                    $counter += $progress;
                }
            } else {

                break;

            }

            $new_str .= substr($str, $i, 1);

        }

        $new_str .= substr($str, $i);
        $new_str .= substr($iv, $progress - 1);

        $new_str_half = (int)ceil(strlen($new_str) / 2);

        $new_str = substr($new_str, 0, $new_str_half) . $hmac . substr($new_str, $new_str_half, $new_str_half);

        return base64_encode($new_str);

    }

    protected function cryptUnCombine($str, $ivlen)
    {

        $crypt_data = [];

        $str = base64_decode($str);

        $hash_position = (int)ceil(strlen($str) / 2 - $this->hasheLength / 2);

        $crypt_data['hmac'] = substr($str, $hash_position, $this->hasheLength);

        $str  = str_replace($crypt_data['']);

        exit();
    }

}