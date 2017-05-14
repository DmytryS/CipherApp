<?php

namespace app\models;

class DesMethod
{
    public static function encrypt($InputString,$key,$alphabet){
       // if (count(self::check_inputstring($InputString,$alphabet))==15 && count(self::check_inputstring($key,$alphabet))==15) {
            # get shorter version of sha256
            $key =substr(hash('sha256', $key),0,8);

            $plaintext = $InputString;

            # get random init vector CBC
            $iv_size = mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_CBC);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

            # create encrypted text compatible with AES (размер блока = 128)
            # Compatibple only with strings not ending on 00h
            # (default symbol for extending)
            $ciphertext = mcrypt_encrypt(MCRYPT_DES, $key, $plaintext, MCRYPT_MODE_CBC, $iv);

            # Set init vector at begining for decryption
            $ciphertext = $iv . $ciphertext;

            # convert text to base64
            $ciphertext_base64 = base64_encode($ciphertext);

            return $ciphertext_base64;
  /*      }else{
            return implode(array_merge(['M','E','S','S','A','G','E',' '],
                self::check_inputstring($InputString,$alphabet),["\n",'K','E','Y',' '],
                self::check_inputstring($key,$alphabet)));
        }*/
    }

    public static function decrypt($InputString,$key,$alphabet){
     //   if (count(self::check_inputstring($InputString,$alphabet))==15 && count(self::check_inputstring($key,$alphabet))==15) {

            $key =substr(hash('sha256', $key),0,8);

            $iv_size = mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_CBC);

            $ciphertext_dec = base64_decode($InputString);

            # Get init vector. Vector length ($iv_size) must match
            # with return of mcrypt_get_iv_size()
            $iv_dec = substr($ciphertext_dec, 0, $iv_size);


            $ciphertext_dec = substr($ciphertext_dec, $iv_size);

            # Get encrypted text
            $plaintext_dec = mcrypt_decrypt(MCRYPT_DES, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

            //remove binary zeros
            $plaintext_dec=rtrim($plaintext_dec, "\0");
            return $plaintext_dec;
      /*  }else{
            return implode(array_merge(['M','E','S','S','A','G','E',' '],
                self::check_inputstring($InputString,$alphabet),["\n",'K','E','Y',' '],
                self::check_inputstring($key,$alphabet)));
        }*/
    }

    private function check_inputstring($text, $alphabet){
        if(!is_array($text)){
            $text=preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        }
        $error_OutputString=array('W','r','o','n','g',' ','s','y','m','b','o','l','s',':',' ');
        $i = 0;
        while ($i < count($text)) {
            $index = array_search($text[$i], $alphabet);

            if ($index == 0) {
                array_push($error_OutputString,$text[$i],' ',' ');
            }
            $i++;
        }
        return $error_OutputString;
    }
}
?>