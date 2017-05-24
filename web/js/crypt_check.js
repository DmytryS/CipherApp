function check_fields(){

    $('#CryptForm').data('yiiActiveForm').submitting = true;
    $('#CryptForm').yiiActiveForm('validate',true);
    if($('#CryptForm').find('.has-error').length) {
        return false;
    }
    else
        return true;
}

$('#InputString').focusout(function() {
    check_fields();
});

$('#key').focusout(function() {
    check_fields();
});


$('#InputString').change(function() {
    check_fields();
});

$('#key').change(function() {
    check_fields();
});


function SendCrypt(IsEncrypt,IsBrute)
{
    var selected_method = document.getElementById("crypt_methods");
    var selected_lang = document.getElementById("lang_list");
    var selected_offset_by = document.getElementById("set_offset_by");

    if ($('#InputString').val() == null || $('#InputString').val() =="")
    {
        $('#InputString').focus();
        $('#InputString').blur();
    }
    if ($('#key').val() == null || $('#key').val() == "") {
        $('#key').focus();
        $('#key').blur();
    }

    if (check_fields()) {
        var data={
            "InputString": $("#InputString").val(),
            "key": $("#key").val() ,
            "IsEncrypt":IsEncrypt,
            "IsBrute":IsBrute,
            "selected_method": selected_method.options[selected_method.selectedIndex].value,
            "selected_lang": selected_lang.options[selected_lang.selectedIndex].value,
            "OffsetBy": selected_offset_by.options[selected_offset_by.selectedIndex].value,
            "_csrf" : "<?=Yii::$app->request->getCsrfToken()?>"
        };
        $.ajax({
            url: '/web/site/crypt',
            type: 'post',
            dataType: "json",
            data:data,
            success: function (data) {
                $('#InputString').val(data.result);
            }
        });
    }

}

var holder = document.getElementById('InputString');
var key_holder = document.getElementById('key');

function save_file() {
    showout = $("#InputString").val();
    var filename = prompt("File name? "/*, "data.txt"*/);
    if (filename === null) {
        return false; //break out of the function early
    }

    var blob = new Blob([showout], {
        type: "text/plain;charset=utf-8"
    });

    saveAs(blob, filename);
    $('#myModal').modal('hide');

};


$(document).on('change', '#open', function(e) {
    e.preventDefault();

    var file = e.target.files[0];

    if (file.type != "text/plain"){
        alert("Load only palin text files");
        return false;
    }

    reader = new FileReader();


    reader.onload = function(event) {
        //console.log(event.target);
        holder.value = event.target.result;
        $('#myModal').modal('hide');
    };
    //console.log(file);
    reader.readAsText(file);

    return false;
});



$(document).on('change', '#open_key', function(e) {
    e.preventDefault();

    var file = e.target.files[0];

    if (file.type != "text/plain"){
        alert("Load only palin text files");
        return false;
    }

    reader = new FileReader();


    reader.onload = function(event) {
        //console.log(event.target);
        key_holder.value = event.target.result;
        $('#myModal').modal('hide');
    };
    //console.log(file);
    reader.readAsText(file);

    return false;
});


holder.ondrop = function(e) {
    // this.className = '';
    e.preventDefault();

    var file = e.dataTransfer.files[0];

    if (file.type != "text/plain"){
        alert("Load only palin text files");
        return false;
    }

    reader = new FileReader();
    reader.onload = function(event) {
        //console.log(event.target);
        holder.value = event.target.result;
    };
    //console.log(file);
    reader.readAsText(file);

    return false;
};


$("#crypt_methods").on('change', function(){
        switch($(this).val()) {
            case "0":
                $('.field-set_offset_by').hide();
                break;
            case "1":
                $('.field-set_offset_by').show();
                break;
            case "2":
                $('.field-set_offset_by').hide();
                break;
        }
});

$( document ).ready(function() {
    $('.field-set_offset_by').hide();
});



    //Change the key size value for new keys
$(".change-key-size").each(function (index, value) {
        var el = $(value);
        var keySize = el.attr('data-value');
        el.click(function (e) {
            var button = $('#key-size');
            button.attr('data-value', keySize);
            button.html(keySize + ' bit <span class="caret"></span>');
            e.preventDefault();
        });
    });


var generateKeys = function () {

    $('#animation').modal('show');
    var spinner = new Spinner().spin();
    document.getElementById('animation').appendChild(spinner.el);


        var sKeySize = $('#key-size').attr('data-value');
        var keySize = parseInt(sKeySize);
        var crypt = new JSEncrypt({default_key_size: keySize});
        var async = $('#async-ck').is(':checked');
        var dt = new Date();
        var time = -(dt.getTime());
        if (async) {
            $('#time-report').text('.');
            var load = setInterval(function () {
                var text = $('#time-report').text();
                $('#time-report').text(text + '.');
            }, 500);
            crypt.getKey(function () {
                clearInterval(load);
                dt = new Date();
                time += (dt.getTime());
                $('#time-report').text('Generated in ' + time + ' ms');
                $('#privkey').val(crypt.getPrivateKey());
                $('#pubkey').val(crypt.getPublicKey());
            });
            return;
        }
        crypt.getKey();
        dt = new Date();
        time += (dt.getTime());
      //  $('#time-report').text('Generated in ' + time + ' ms');
      //  $('#privkey').val(crypt.getPrivateKey());
      //  $('#pubkey').val(crypt.getPublicKey());


    var filename = "private";
    var blob = new Blob([crypt.getPrivateKey()], {
        type: "text/plain;charset=utf-8"
    });
    saveAs(blob, filename);

    var filename = "public";
    var blob = new Blob([crypt.getPublicKey()], {
        type: "text/plain;charset=utf-8"
    });
    saveAs(blob, filename);
    $('#animation').modal('hide');
};

// If they wish to generate new keys.
$('#generate').click(generateKeys);

