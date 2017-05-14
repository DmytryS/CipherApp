<?php

namespace app\models;
use Exception;
class RsaMethod
{
    public static function encrypt($InputString,$key){
        //$public_key = file_get_contents('public.pem');
        $public_key = $key;
        if (openssl_public_encrypt($InputString, $encrypted, $public_key))
            $data = base64_encode($encrypted);
        else
            throw new Exception('Unable to encrypt data. Perhaps it is bigger than the key size?');

        return $data;
    }

    public static function decrypt($InputString,$key){
        $private_key = $key;
        //$private_key = file_get_contents('../private.pem');
        //openssl_pkey_get_private($private_key, "phrase");
        if (openssl_private_decrypt(base64_decode($InputString), $decrypted,$private_key)) //$this->privkey
            $data = $decrypted;
        else
            $data = '';

        return $data;
    }
}
?>