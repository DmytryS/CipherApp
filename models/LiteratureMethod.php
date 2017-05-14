<?php

namespace app\models;

class LiteratureMethod
{
    public static function encrypt($InputString,$key,$alphabet){

        $InputString=preg_split('//u', $InputString, -1, PREG_SPLIT_NO_EMPTY);

        $ccss = null;
        $OutputString = array();

        if (count(self::check_inputstring($InputString,$alphabet))==15 && count(self::check_inputstring(preg_split('//u', $key, -1, PREG_SPLIT_NO_EMPTY),$alphabet))==15) {
            $key=explode("\n",$key);
            $key[0]=$key[0]."\n";
            self::SetKey($ccss, $key);

            for ($i = 0; $i < count($InputString); $i++) {
                if (self::GetRandomCode(self::FindAllCodesForCharacters($ccss, $key, $InputString[$i])) == null) {
                    array_push($OutputString, " Error: missing symbol:" . $InputString[$i]);
                    break;
                }
                array_push($OutputString, self::GetRandomCode(self::FindAllCodesForCharacters($ccss, $key, $InputString[$i])) . " ");
            }
            return implode($OutputString);
        } else{
            return implode(array_merge(['M','E','S','S','A','G','E',' '],
                self::check_inputstring($InputString,$alphabet),["\n",'K','E','Y',' '],
                self::check_inputstring(preg_split('//u', $key, -1, PREG_SPLIT_NO_EMPTY),$alphabet)));
        }
    }

    public static function decrypt($InputString,$key,$alphabet){

        $InputString=preg_split('//u', $InputString, -1, PREG_SPLIT_NO_EMPTY);
        //$key=preg_split('//u', $key, -1, PREG_SPLIT_NO_EMPTY);


        $ccss = null;

        $OutputString = array();

        if (count(self::check_inputstring($InputString,$alphabet))==15 && count(self::check_inputstring(preg_split('//u', $key, -1, PREG_SPLIT_NO_EMPTY),$alphabet))==15) {
            $key=explode("\n",$key);
            $key[0]=$key[0]."\n";
            self::SetKey($ccss, $key);

            for ($i = 0; $i < count($InputString); $i += 5)
            {
                if (self::GetLetterForNum($ccss,$key,$alphabet,preg_split('//u',(mb_substr(implode($InputString),$i,4)), -1, PREG_SPLIT_NO_EMPTY)) == count($alphabet)-1)
                {
                    array_push($OutputString," ERROR! Missing CCSS code.");
                    break;
                }
                array_push($OutputString,self::GetLetterForNum($ccss,$key,$alphabet,preg_split('//u',(mb_substr(implode($InputString),$i,4)), -1, PREG_SPLIT_NO_EMPTY)));
            }
            return implode($OutputString);
        } else{
            return implode(array_merge(['M','E','S','S','A','G','E',' '],
                self::check_inputstring($InputString,$alphabet),["\n",'K','E','Y',' '],
                self::check_inputstring(preg_split('//u', $key, -1, PREG_SPLIT_NO_EMPTY),$alphabet)));
        }
    }


private static function SetKey(&$ccss,$key) {
    $ccss= array();
    for ($i = 0; $i < count($key); $i++) {
        for ($j = 0; $j < count(preg_split('//u',$key[$i], -1, PREG_SPLIT_NO_EMPTY)); $j++) {
            $ccss[$i][$j] = "".($i + 10).($j + 10)."";
        }
    }
}

private static function FindAllCodesForCharacters($ccss,$key,$ch) {
    $CodeList=array();
    for ($i = 0; $i < count($key); $i++) {
        for ($j = 0; $j < count(preg_split('//u',$key[$i], -1, PREG_SPLIT_NO_EMPTY)); $j++) {
            $tmp=preg_split('//u',$key[$i], -1, PREG_SPLIT_NO_EMPTY);
            if ($ch == $tmp[$j]) {
                array_push($CodeList,$ccss[$i][$j]);
            }
        }
    }
    return $CodeList;
}

private static function GetRandomCode($CodeList){
    try {
        if(count($CodeList)==0) return "Incompatible key and text";
        srand();//count($CodeList)
        $index = rand(0, count($CodeList)-1);
        return $CodeList[$index];
    }
    catch(Exception $e){
            return "Incompatible key and text";
        }
    }

    private static function GetLetterForNum($ccss, $key, $alphabet, $st){
        for ($i = 0; $i < count($key); $i++) {
            for ($j = 0; $j < count(preg_split('//u',$key[$i], -1, PREG_SPLIT_NO_EMPTY)); $j++) {
                if ($ccss[$i][$j] == implode($st)) {
                    $tmp=preg_split('//u',$key[$i], -1, PREG_SPLIT_NO_EMPTY);
                    return $tmp[$j];
                }
            }
        }
        return count($alphabet);
    }

    private function check_inputstring($text, $alphabet){
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