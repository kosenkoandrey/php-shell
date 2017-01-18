<!DOCTYPE html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<meta name="ql-template-name" content="Backgroundr Centered">
<meta name="ql-template-published" content="true">
<meta name="ql-template-features" content="optin, colors">
<meta name="ql-template-colors" content="blurd">
<link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/modules/pages/products/garderob100/form/style_form.css">
<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,400italic,600italic,700italic" rel="stylesheet" type="text/css">
<title>Гардероб на 100%</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script> 
      jQuery(document).ready(function(){
        new KOLResponse();
        new KOLValidation();
    });
    </script>
<script type="text/javascript">
DOMReady=function(A){var C=false;ready=function(){if(!C){A();C=true}};try{document.addEventListener("DOMContentLoaded",ready,false)}catch(B){timer=setInterval(function(){if(/loaded|complete/.test(document.readyState)){clearInterval(timer);ready()}},10)}window.onload=function(){ready()}};
new DOMReady(function(){var search=(/^\?/.test(window.location.search))?window.location.search.substr(1):window.location.search;var options=search.split('&');for(var i=0;i<options.length;i++){var pair=options[i].split('=');if(pair.length==2){
//var elements=document.getElementById(pair[0]);if(elements&&elements.length>0){for(var j=0;j<elements.length;j++){if(typeof elements[j]=='object'){elements[j].value=decodeURIComponent(pair[1]+'')}}}}}
var elements=document.getElementById(pair[0]);if(elements){if(typeof elements=='object'){elements.value=decodeURIComponent(pair[1]+'')}}}}
//document.getElementById('metka').value = pair[1];
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script language="JavaScript">

function mes() 
{
	alert("Чтобы записаться, заполните форму.");  
}

        function SR_trim(f) {
            return f.toString().replace(/^[ ]+/, '').replace(/[ ]+$/, '');
        }

        function sleep(numberMillis) {
            var now = new Date();
            var exitTime = now.getTime() + numberMillis;
            while (true) {
                now = new Date();
                if (now.getTime() > exitTime) return;
            }
        };

        function SR_submit(f) {
            f["tel"].value = SR_trim(f["tel"].value);
            if ((SR_focus = f["tel"]) && f["tel"].value.replace(/^[ ]+/, '').replace(/[ ]+$/, '').length < 1 || (SR_focus = f["tel"]) && f["tel"].value.replace(/^[ ]+/, '').replace(/[ ]+$/, '').length < 6) {
                alert("Укажите ваш телефон");
                SR_focus.focus();
                return false;
            }
            f["firstname"].value = SR_trim(f["firstname"].value);
            if ((SR_focus = f["firstname"]) && f["firstname"].value.replace(/^[ ]+/, '').replace(/[ ]+$/, '').length < 1 || (SR_focus = f["firstname"]) && f["firstname"].value.replace(/^[ ]+/, '').replace(/[ ]+$/, '').length < 2) {
                alert("Укажите ваше имя");
                SR_focus.focus();
                return false;
            }
            
            $('#submit-preform').attr( "disabled", "disabled" ).html('Подождите...');
            
            $.ajax({
                        type: 'POST',
                        url: '<?= APP::Module('Routing')->root ?>tunnels/api/tags/add.json',
                        async: false,
                        data: {
                            'user_tunnel_id'  : <?= APP::Module('Routing')->get['user_tunnel_id'] ?>,
                            'label_id'     : 'preentry',
                            'token'    : '',
                            'info'    : ''                         
                        },
                        success: function(data) {
                            
                            $.ajax({
                                type: 'POST',
                                url: '<?= APP::Module('Routing')->root ?>users/api/about/add.json',
                                async: false,
                                data: {
                                                                'user'  : <?= APP::Module('Routing')->get['user_id'] ?>,
                                                                'item'     : 'tel',
                                                                'value'    : f["tel"].value
                                },
                                success: function(data) {
                                    $.ajax({
                                        type: 'POST',
                                        url: '<?= APP::Module('Routing')->root ?>users/api/about/add.json',
                                        async: false,
                                        data: {
                                                                        'user'  : <?= APP::Module('Routing')->get['user_id'] ?>,
                                                                        'item'     : 'firstname',
                                                                        'value'    : f["firstname"].value
                                        },
                                        success: function(data) {
                                            window.top.location.href = "http://www.glamurnenko.ru/garderob100/activate/wait-list-final.php";
                                        }
                                    });
                                }
                            });
                        }
                    });
            
                    

return false;
}
function oncen(){ 
	document.getElementById('tel').placeholder='';
}
function oncen2(){ 
	document.getElementById('firstname').placeholder='';
}
</script>
</head>
<?php
$tel = APP::Module('DB')->Select(
    APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
    ['value'], 'users_about',
    [
        ['user', '=', APP::Module('Routing')->get['user_id'], PDO::PARAM_INT],
        ['item', '=', 'tel', PDO::PARAM_STR]
    ]
);

$firstname = APP::Module('DB')->Select(
    APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
    ['value'], 'users_about',
    [
        ['user', '=', APP::Module('Routing')->get['user_id'], PDO::PARAM_INT],
        ['item', '=', 'firstname', PDO::PARAM_STR]
    ]
);
?>
<body>
  <div class="wrap">
    <div class="optin animated fadeInDown">
      <h1 ql-id="headline" ql-name="Headline" ql-editable="text">Предварительный список…</h1>
      <div class="inner-wrap">
        <p ql-id="call_to_action" ql-name="Call To Action" ql-editable="text" style="font-size: 0.9em; text-align:center">Заполните форму и нажмите кнопку <br/>"Получить скидку!"</p>
        <form style="margin: 0; padding: 0;" name="SR_form" target="_parent" action="<?= APP::Module('Routing')->root ?>tunnels/api/tags/add.json" method="post" onSubmit="return SR_submit(this)">
          <div ql-id="optin_email" class="group">
            <input type="text" value="<?= APP::Module('Routing')->get['email'] ?>" name="email" id="field_email" readonly="false"><p class="optin-error"></p>
			<input type="text" name="firstname" id="firstname" placeholder="Ваше Имя" value="<?= $firstname ?>" onFocus="oncen2();">
			<input type="text" name="tel" id="tel" placeholder="телефон" value="<?= $tel ?>" onFocus="oncen();">
                            <input id="return" name="return" type="hidden" value=""/>
          </div>
          <button id="submit-preform" ql-id="call_to_action_button" ql-name="Call To Action Button" ql-editable="button" type="submit" class="btn">Получить скидку</button>
        </form>
        <p class="small" style=" text-align:center;"><i class="ss-icon"><img src="http://www.glamurnenko.ru/garderob100/wp-content/themes/garderob100-theme/squeezes/video-squeeze-01/look.png" style="padding-right: 4px;"> </i> <span ql-id="footer" ql-editable="text">  Нажимая на кнопку, я даю согласие на обработку персональных данных, получение рекламной информации и соглашаюсь с условиями и <a href="http://www.glamurnenko.ru/pers.html" target="_blank">политикой приватности</a>.</span></p>
      </div>
    </div>
  </div>
</body>
</html>
