<?php
namespace app\models;
include('includes/RSA.php');
use Crypt_RSA;
use Exception;
class RsaMethod
{
    public static function encrypt($InputString,$key){
        $public_key = $key;
        $ciphertext = $InputString;//base64_encode($InputString);
        $rsa = new Crypt_RSA();
        $rsa->loadKey($public_key);
        try{
            $data = base64_encode($rsa->encrypt($ciphertext));
        }
        catch (Exception $e){
            $data=$e->getMessage();
        };


        return $data;
    }

    public static function decrypt($InputString,$key){
        $private_key = $key;
        $ciphertext = base64_decode($InputString);
        $rsa = new Crypt_RSA();
        $rsa->loadKey($private_key);
        try{
           $data = $rsa->decrypt($ciphertext);
        }
        catch (Exception $e){
            $data=$e->getMessage();
        };
        return $data;
    }
}
