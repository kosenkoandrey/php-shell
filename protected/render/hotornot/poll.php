<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="http://www.glamurnenko.ru/favicon.ico">

    <title>Glamurnenko.ru - Hot or not</title>

    <link href="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/bootstrap.min.css" rel="stylesheet">
    <link href="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!--[if lt IE 9]><script src="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/jquery-ui.css">
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    
    <script type="text/javascript" src="http://vk.com/js/api/share.js?90" charset="windows-1251"></script>
    
    <style>
        body {
            background-image: url(https://pult.glamurnenko.ru/public/WebApp/resources/views/full/langs/ru_RU/types/extensions/EBilling/products/pages/garderob100/sale/images/bg4.jpg);
            background-repeat:repeat;
            padding: 0;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            line-height: 25px;
        }
        #logo {
            position: absolute;
            right: 0;
            left: 0;
            z-index: 1030;
            text-align: center;
            padding: 50px;
        }
        .poll {
            display: none;
            margin-top: 60px;
        }
        .photo {
            box-shadow: 0 0 95px rgba(0,0,0,0.3);
        }
        .photo:hover {
            box-shadow: 0 0 95px #ef1c8e;
        }
        .photo.l {
            float: right;
        }
        .photo.r {
            float: left;
        }
        .photo > h1 {
            font-size: 120px;
        }

        #login, #final {
            display: none;
            position: absolute;
            width: 100%;
            left: 0;
            top: 0;
            background: #FFFFFF;
            opacity: 0.93;
            z-index: 1041;
        }
        #final > .center {
            position: absolute;
            width: 600px;
            height: 300px;
            left: 50%;
            top: 50%;
            margin-left: -300px;
            margin-top: -150px;
            text-align: center;
            font-size: 20px;
        }
        #login > .center {
            position: absolute;
            width: 300px;
            height: 300px;
            left: 50%;
            top: 50%;
            margin-left: -150px;
            margin-top: -150px;
            text-align: center;
            font-size: 20px;
        }
        #login > .center a {
            font-size: 82px;
        }
        
        #loading {
            display: none;
            position: absolute;
            width: 300px;
            height: 300px;
            left: 50%;
            top: 50%;
            margin-left: -150px;
            margin-top: -150px;
            z-index: 1031;
        }
        
        #finish {
            display: none;
            position: absolute;
            width: 100%;
            left: 0;
            top: 0;
            background: #FFFFFF;
            opacity: 0.9;
            z-index: 1032;
        }
        #finish > .center {
            position: absolute;
            width: 600px;
            height: 600px;
            left: 50%;
            top: 50%;
            margin-left: -300px;
            margin-top: -200px;
            text-align: center;
            font-size: 20px;
        }
        h3 {
            display: inline-block;
            background: #FFFFFF;
            padding: 20px;
            opacity: 0.93;
            width: 450px;
            box-shadow: 0 0 25px rgba(0,0,0,0.3);
            margin-bottom: 50px;
        }
        h3.l {
            float: right;
        }
        h3.r {
            float: left;
        }
        </style>
    </head>
    <body>
        <div id="final">
            <div class="center">
                Спасибо за внимание!<br>Голосование завершилось.<br><br>Победители будут объявлены в пятницу,<br>23 декабря в 17:00 МСК в прямом эфире по ссылке:
                <br><br>
                <a target="_top" href="http://www.glamurnenko.ru/products/online-iqv61HwXl70.php" class="btn btn-success btn-lg btn-block"><i class="fa fa-microphone" aria-hidden="true"></i> ПРЯМОЙ ЭФИР С ВЫБОРОМ ПОБЕДИТЕЛЯ</a>
                <br>
                Также во время прямого эфира мы проведем: 
                <ul>
                <li>розыгрыш наших тренингов среди всех участников конкурса
                </li>розыгрыш наших тренингов среди всех голосовавших
                </ul>
                Присоединяйтесь к прямому эфиру!
                <br><br>
                А вот здесь смотрите результаты голосования: <br><a target="_top" href="http://www.glamurnenko.ru/missnewyear/top/">рейтинг участников</a>
            </div>
        </div>
        <div id="login">
            <div class="center">
                <b>Чтобы проголосовать за участниц, авторизуйтесь через vk или fb</b>
                <br><br>
                <a target="_top" href="http://oauth.vk.com/authorize?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_vk_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/vk', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode('http://www.glamurnenko.ru/missnewyear/') . '"}')])) ?>"><i class="fa fa-vk" aria-hidden="true"></i></a> <span style="top: -20px; display: inline-block; position: relative;">или</span> <a target="_top" href="https://www.facebook.com/dialog/oauth?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_fb_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/fb', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode('http://www.glamurnenko.ru/missnewyear/') . '"}')])) ?>"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
            </div>
        </div>
        <div id="finish">
            <div class="center">
                <b>Спасибо!<br>Вы дошли до конца голосования.<br>Больше участниц нет.</b>
                <br><br>
                Поделитесь со своими друзьями ссылкой на эту страницу.
                <br><br>
                <div class="row">
                    <div class="col-sm-6">
                        <div style="float: right">
                            <script type="text/javascript">document.write(VK.Share.button({url: "http://www.glamurnenko.ru/missnewyear/"},{type: "link", text: "Share"}));</script>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div style="float: left">
                            <iframe src="https://www.facebook.com/plugins/share_button.php?href=http%3A%2F%2Fwww.glamurnenko.ru%2Fmissnewyear%2F&layout=button&mobile_iframe=true&width=94&height=20&appId" width="94" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                        </div>
                    </div>
                </div>
                <br>
                И следите за результатами голосования.
                <br><br>
                <a target="_top" href="http://www.glamurnenko.ru/missnewyear/top/" class="btn btn-default btn-lg" style="margin-bottom: 50px"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Результаты голосования</a>
            </div>
        </div>
        <img id="loading" src="https://www.bensherman.co.uk/img/skin/loading.gif">
        <div class="container">
            <div id="logo">
                <img src="<?= APP::Module('Routing')->root ?>public/modules/hotornot/images/logo.png"><img src="<?= APP::Module('Routing')->root ?>public/modules/hotornot/images/hotornot.png">
            </div>
            <div class="row">
                <div class="col-sm-12" style="margin-top: 160px; text-align: center; font-size: 18px;">
                    Правила просты: выбирайте, кто из двух участниц вам больше всего нравится.
                    <br>
                    Победители получат iPad, подарочные сертификаты в интернет-магазины и наши курсы по стилю.
                    <br>
                    <a href="http://www.glamurnenko.ru/blog/konkurs.php" target="_blank">Подробности о подарках</a>. Или смотрите: <a target="_blank" href="http://www.glamurnenko.ru/missnewyear/top/">текущий рейтинг участников</a>.
                </div>
            </div>
            <div class="row poll">
                <div class="col-sm-6">
                    <div class="box burst-circle strawberry photo l">
                        <div class="caption"></div>
                        <img src="#">
                        <h1><span class="glyphicon glyphicon-heart" aria-hidden="true"></span></h1>
                    </div>
                    <h3 class="l"></h3>
                </div>
                <div class="col-sm-6">
                    <div class="box burst-circle strawberry photo r">
                        <div class="caption"></div>
                        <img src="#">
                        <h1><span class="glyphicon glyphicon-heart" aria-hidden="true"></span></h1>
                    </div>
                    <h3 class="r"></h3>
                </div>
            </div>
        </div>

        <script src="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/ie10-viewport-bug-workaround.js"></script>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script>
        $(function() {
            function setheight(){
                $('#login, #finish, #final').css({
                    height: $(window).height() + 'px'
                });
            }
            
            setheight();
            
            $(window).resize(function(){
                setheight();
            });

            function getpeople() {
                $('#loading').fadeIn();
                $('.poll').fadeOut();
                
                $.ajax({
                    type: 'post',
                    url: '<?= APP::Module('Routing')->root ?>hotornot/people/api/get.json',
                    success: function(people) {
                        if (people.length === 2) {
                            $('.photo.l img').attr('src', people[0].image).data('id', people[0].id);
                            $('.photo.r img').attr('src', people[1].image).data('id', people[1].id);
                            
                            $('.photo.l').siblings('h3.l').html(people[0].description);
                            $('.photo.r').siblings('h3.r').html(people[1].description);

                            $('.photo').removeAttr('style').removeClass('a').hide().fadeIn();

                            $('#loading').fadeOut();
                            $('.poll').fadeIn();
                        } else {
                            $('#loading').fadeOut();
                            $('#finish').fadeIn();
                        }
                    }
                });
            }

            getpeople();
            $('#final').show();

            $('.photo').on('click', function() {
                <?
                if (APP::Module('Users')->user['role'] == 'default') {
                    ?>
                    $('#login').fadeIn();
                    return false;
                    <?
                }
                ?>
            
                $(this).addClass('a');
                
                var hot = $('img', this).data('id');
                var not = $('.photo:not(.a) img').data('id');

                $(this).effect('drop', {direction: 'up'}, 400);
                $('.photo:not(.a)').effect('drop', {direction: 'down'}, 400);

                $.ajax({
                    type: 'post',
                    url: '<?= APP::Module('Routing')->root ?>hotornot/people/api/rate.json',
                    data: {
                        hot: hot,
                        not: not
                    },
                    success: function(people) {
                        getpeople();
                    }
                });

                return false;
            });
        });
        </script>
    </body>
</html>