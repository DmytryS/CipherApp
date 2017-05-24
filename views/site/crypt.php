<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\AppAsset;
AppAsset::register($this);
?>


        <?php $f = ActiveForm::begin([
            'enableClientValidation' => true,
            'id' => 'CryptForm',

        ]); ?>


            <?=$f->field($form, 'InputString')->textarea(['rows' => '10','value' => null,'id' => 'InputString'])?>

            <button class="btn btn-default" type="button" data-toggle="modal" data-target="#myModal">Files settings</button>

            <?=$f->field($form, 'key')->textarea(['rows' => '7','value' => null,'id' => 'key'])  ?>


<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title">Заголовок окна</h4>
            </div>

            <div class="col-lg-6 col-sm-6 col-12">
                <label class="btn btn-primary">
                    Open file
                    <input id="open" type="file" accept="text/plain" style="display: none;">
                </label>
            </div>

            <div class="col-lg-6 col-sm-6 col-12">
                <label class="btn btn-primary">
                    Open file for key
                    <input id="open_key" type="file" accept="text/plain" style="display: none;">
                </label>
            </div>

            <button class="btn btn-primary" type="button" onclick="save_file()">Save to file</button>

            <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Закрыть</button></div>
        </div>
    </div>
</div>

            <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
            <?= Html::button('Decrypt',['class'=>"btn btn-default",'onclick'=>'SendCrypt(false,false);'])?>
            <?= Html::button('Encrypt',['class'=>"btn btn-default",'onclick'=>'SendCrypt(true,false);'])?>
            <?= Html::button('Brute',['class'=>"btn btn-default",'onclick'=>'SendCrypt(false,true);'])?>

            <br><br>
            <div class="btn-group" style="float: left">
                <div class="input-group">
                    <span class="input-group-addon">Key Size</span>
                    <button class="btn btn-default dropdown-toggle" id="key-size" type="button" data-value="1024" data-toggle="dropdown">1024 bit <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a class="change-key-size" data-value="512" href="#">512 bit</a></li>
                        <li><a class="change-key-size" data-value="1024" href="#">1024 bit</a></li>
                        <li><a class="change-key-size" data-value="2048" href="#">2048 bit</a></li>
                        <li><a class="change-key-size" data-value="4096" href="#">4096 bit</a></li>
                    </ul>
                </div>
            </div>
            <?= Html::button('Generate RSA pair',['class'=>"btn btn-default",'id'=>'generate'])?>
            <br><br>

            <?= $f->field($form, 'lang_list')->dropDownList($lang_list,['id' => 'lang_list'])  ?>
            <?= $f->field($form, 'crypt_methods')->dropDownList($crypt_methods,['id' => 'crypt_methods'])  ?>
            <?= $f->field($form, 'set_offset_by')->dropDownList($set_offset_by,['id' => 'set_offset_by'])  ?>

<div class="modal" id="animation"><!-- Place at bottom of page --></div>
        <?php ActiveForm::end(); ?>

