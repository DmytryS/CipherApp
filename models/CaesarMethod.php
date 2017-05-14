<?php

namespace app\models;

class CaesarMethod
{

    public static function encrypt($InputString,$key,$alphabet){
        $InputString=preg_split('//u', $InputString, -1, PREG_SPLIT_NO_EMPTY);
        if (count(self::check_inputstring($InputString,$alphabet))==15 && count(self::check_inputstring($key,$alphabet))==15) {
            return implode(self::calculate($InputString,$key,$alphabet,true));
        }else{
            return implode(array_merge(['M','E','S','S','A','G','E',' '],
                self::check_inputstring($InputString,$alphabet),["\n",'K','E','Y',' '],
                self::check_inputstring($key,$alphabet)));
        }
    }

    public static function decrypt($InputString,$key,$alphabet){
        $InputString=preg_split('//u', $InputString, -1, PREG_SPLIT_NO_EMPTY);
        if (count(self::check_inputstring($InputString,$alphabet))==15 && count(self::check_inputstring($key,$alphabet))==15) {
            return implode(self::calculate($InputString,-1*$key,$alphabet,false));
        }else{
            return implode(array_merge(['M','E','S','S','A','G','E',' '],
                self::check_inputstring($InputString,$alphabet),["\n",'K','E','Y',' '],
                self::check_inputstring($key,$alphabet)));
        }
    }

    private function calculate($InputString,$key,$alphabet,$encrypt) {
        $OutputString=array();

        for($i=0; $i < count($InputString);$i++) {
            $index = array_search($InputString[$i], $alphabet);
            $offset=($index + $key % (count($alphabet)-1) + (count($alphabet)-1)) % (count($alphabet)-1);

            if ($offset == 0) {
                if($encrypt) {
                    $offset = 1;
                }else {
                    $offset = count($alphabet)-1;
                }
            }
            $OutputString[$i] = $alphabet[$offset];
        }
        return $OutputString;
    }


    private function check_inputstring($InputString,$alphabet){
        $error_OutputString=array('W','r','o','n','g',' ','s','y','m','b','o','l','s',':',' ');
        $i = 0;
        while ($i < count($InputString)) {
            $index = array_search($InputString[$i], $alphabet);

            if ($index == 0) {
                array_push($error_OutputString,$InputString[$i],' ',' ');
            }
            $i++;
        }
        return $error_OutputString;
    }
}
?>