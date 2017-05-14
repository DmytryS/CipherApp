<?php
namespace app\models;

use Yii;
use yii\base\Model;

include('CaesarMethod.php');
include('TritemiumMethod.php');
include('GammaMethod.php');

class CryptForm extends Model
{

    public $InputString;
    public $key;

    private $OutputString;

    private $IsEncrypt;
    private $alphabet;
    private $selected_method;
    private $selected_lang;
    private $IsBrute;
    private $OffsetBy;



    private $rus = ['',
    'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
    'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
    'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
    'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
    ];
    private $eng=['',
      'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
      'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
    ];

    private $ukr=['',
        'а','б','в','г','ґ','д','е','є','ж','з','и','і','ї','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ь','ю','я',
        'А','Б','В','Г','Ґ','Д','Е','Є','Ж','З','И','І','Ї','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ь','Ю','Я'
    ];

    private $num=[
        '0','1','2','3','4','5','6','7','8','9'
    ];

    private $symb=[
        '.','!','@','#','№','$',';','%','^',':','&','?','*','(',')','-','=','+',' ','"',',','_',"\n"
    ];

    public $crypt_methods=[
        '0'=>'Caesar Method',
        '1'=>'Tritemium Method',
        '2'=>'Gamma Method',
        '3'=>'Literature Method',
        '4'=>'Des Method',
        '5'=>'Rsa Method'
    ];

    public $lang_list=[
        '0'=>'eng',
        '1'=>'rus',
        '2'=>'ukr',
    ];
    public $set_offset_by=[
        '0'=>'Linear equation',
        '1'=>'Non linear equation',
        '2'=>'Text',
    ];

    public $rsa_key_size=[
        '512'=>'512',
        '1024'=>'1024',
        '2048'=>'2048',
        '4096'=>'4096',
    ];

    public function rules(){
        return [
            [['InputString'],'required'],
        ];
    }

     public function __construct()
    {
        return $this->alphabet=array(array_merge($this->eng,$this->num,$this->symb),array_merge($this->rus,$this->num,$this->symb),array_merge($this->ukr,$this->num,$this->symb));
    }

    public function crypt(){
        switch ($this->selected_method) {
            case 0:
                if($this->IsBrute=="true"){
                    for($i=1;$i<count($this->alphabet[$this->selected_lang]);$i++){
                        $this->OutputString=$this->OutputString.CaesarMethod::encrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang])."\n";
                    }
                }else{
                    if($this->IsEncrypt=="true"){
                        $this->OutputString=CaesarMethod::encrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang]);
                    }else{
                        $this->OutputString=CaesarMethod::decrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang]);
                    }
                }
                return $this->OutputString;
                break;
            case 1:
                if($this->IsEncrypt=="true"){
                    return TritemiumMethod::encrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang],$this->OffsetBy);
                }else{
                    return TritemiumMethod::decrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang],$this->OffsetBy);
                }
                break;
            case 2:
                if($this->IsEncrypt=="true"){
                    return GammaMethod::encrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang]);
                }else{
                    return GammaMethod::decrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang]);
                }
                break;
            case 3:
                if($this->IsEncrypt=="true"){
                    return LiteratureMethod::encrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang]);
                }else{
                    return LiteratureMethod::decrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang]);
                }
                break;
            case 4:
                if($this->IsEncrypt=="true"){
                    return DesMethod::encrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang]);
                }else{
                    return DesMethod::decrypt($this->InputString,$this->key,$this->alphabet[$this->selected_lang]);
                }
                break;
            case 5:
                if($this->IsEncrypt=="true"){
                    return RsaMethod::encrypt($this->InputString,$this->key);
                }else{
                    return RsaMethod::decrypt($this->InputString,$this->key);
                }
                break;
            default:
                return "ERROR";

        }


    }



    public function set_data($InputString,$key,$IsEncrypt,$selected_method,$selected_lang,$IsBrute,$OffsetBy){
        $this->InputString=$InputString;
        $this->key=$key;
        $this->IsEncrypt=$IsEncrypt;
        $this->selected_method=$selected_method;
        $this->selected_lang=$selected_lang;
        $this->IsBrute=$IsBrute;
        $this->OffsetBy=$OffsetBy;

    }



}
?>