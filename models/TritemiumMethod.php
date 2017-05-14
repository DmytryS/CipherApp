<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 07.03.2017
 * Time: 17:12
 */

namespace app\models;


class TritemiumMethod
{

    public static function encrypt($InputString,$key,$alphabet,$OffsetBy){
        $InputString=preg_split('//u', $InputString, -1, PREG_SPLIT_NO_EMPTY);
        if (count(self::check_inputstring($InputString,$alphabet))==15 && count(self::check_inputstring($key,$alphabet))==15) {
            return implode(self::calculate($InputString,$key,$alphabet,$OffsetBy,true));
        }else{
            return implode(array_merge(['M','E','S','S','A','G','E',' '],
                self::check_inputstring($InputString,$alphabet),["\n",'K','E','Y',' '],
                self::check_inputstring($key,$alphabet)));
        }
    }

    public static function decrypt($InputString,$key,$alphabet,$OffsetBy){
        $InputString=preg_split('//u', $InputString, -1, PREG_SPLIT_NO_EMPTY);
        if (count(self::check_inputstring($InputString,$alphabet))==15 && count(self::check_inputstring($key,$alphabet))==15) {
            return implode(self::calculate($InputString,$key,$alphabet,$OffsetBy,false));
        }else{
            return implode(array_merge(['M','E','S','S','A','G','E',' '],
                self::check_inputstring($InputString,$alphabet),["\n",'K','E','Y',' '],
                self::check_inputstring($key,$alphabet)));
        }
    }

    private static function calculate($InputString,$key,$alphabet,$OffsetBy,$encrypt)
    {
        $OutputString=array();
        for($i=0;$i < count($InputString);$i++) {
            $index = array_search($InputString[$i], $alphabet);
            $k=self::get_key(count($InputString),$key,$i,$OffsetBy,$alphabet);

            if(!$encrypt) $k*=-1;

            $offset=($index + $k % (count($alphabet)-1) + (count($alphabet)-1)) % (count($alphabet)-1);

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

    private function get_key($InputStringLength,$key,$i,$OffsetBy,$alphabet){
        switch ($OffsetBy) {
            case 0:
                $key=explode(",",$key);
                $k = $key[0] * $i + $key[1];
                while($k>$InputStringLength){
                    $k-=$InputStringLength;
                }
                while($k<0){
                    $k+=$InputStringLength;
                }
                return $k;
                break;
            case 1:
                $key=explode(",",$key);
                $k = $key[0] * $i * $i + $key[1] * $i + $key[2];
                while($k>$InputStringLength){
                    $k-=$InputStringLength;
                }
                while($k<0){
                    $k+=$InputStringLength;
                }
                return $k;
                break;
            case 2:
                $key=preg_split('//u', $key, -1, PREG_SPLIT_NO_EMPTY);
                $keyindex=$i%count($key);
                $k =array_search($key[$keyindex], $alphabet);
                return $k;
                break;
            default:
                return 0;
        }
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