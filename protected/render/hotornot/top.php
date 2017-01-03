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
    <link rel="stylesheet" href="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/magnific-popup.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    
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
        .top {
            margin-top: 0;
        }
        .top table {
            
        }
        .photo {
            width: 120px;
        }
        .num {
            text-align: center;
        }
        .head {
            text-align: center;
            font-weight: bold;
            width: 17%
        }
        .tooltip, .tooltip-inner {
            width: 600px !important;
            max-width: 600px !important;
        }
        </style>
    </head>
    <body>
        <div class="container">
            <div id="logo">
                <img src="<?= APP::Module('Routing')->root ?>public/modules/hotornot/images/logo.png"><img src="<?= APP::Module('Routing')->root ?>public/modules/hotornot/images/hotornot.png">
            </div>
            <div class="row">
                <div class="col-sm-12" style="margin-top: 160px; text-align: center">
                    Следите за рейтингом своих любимых участниц по таблице ниже.
                    <br>
                    Победители получат iPad, подарочные сертификаты в интернет-магазины и наши курсы по стилю.
                    <br>
                    <a href="http://www.glamurnenko.ru/blog/konkurs.php" target="_blank">Подробности о подарках</a>.
                    <br><br>
                    Еще не голосовали? Вам сюда: 
                    <br>
                    <a target="_top" href="http://www.glamurnenko.ru/missnewyear/" class="btn btn-default btn-lg" style="margin-bottom: 50px"><span class="glyphicon glyphicon-play" aria-hidden="true"></span> Начать голосовать</a>
                </div>
            </div>
            <div class="row top">
                <div class="col-sm-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10%"></th>
                                <th style="width: 10%"></th>
                                <th style="width: 15%" class="head"></th>
                                <th class="head">За</th>
                                <th class="head">Против</th>
                                <th class="head">Итого</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($data as $key => $value) {
                                ?>
                            <tr>
                                    <td><br><h1 style="font-size: 76px"><?= $key + 1 ?></h1></td>
                                    <td class="photo"><a class="photo-popup-link" href="<?= APP::Module('Routing')->root ?>hotornot/images/<?= $value['people'] ?>/450x650"><img class="img-thumbnail" src="<?= APP::Module('Routing')->root ?>hotornot/images/<?= $value['people'] ?>/100x144" class="photo"></a></td>
                                    <td class="num"><br><h1 data-original-title="<?= htmlspecialchars($value['description']) ?>" data-toggle="tooltip" data-placement="right" class="matrisHeader" data-container="body" title="" style="font-size: 36px; margin: 25px 0 0 0; padding: 0; display: inline-block; cursor: pointer; border-bottom: 1px dashed #000000">Описание</h1></td>
                                    <td class="num"><br><h1 style="font-size: 76px"><?= $value['hot'] ?></h1></td>
                                    <td class="num"><br><h1 style="font-size: 76px"><?= $value['not'] ?></h1></td>
                                    <td class="num"><br><h1 style="font-size: 76px"><?= $value['total'] ?></h1></td>
                                </tr>
                                <?
                            }
                            ?>
                            
                        </tbody>
                    </table>
                    <?
                    if (!isset(APP::Module('Routing')->get['all'])) {
                        ?>
                        <br>
                        <a target="_top" href="http://www.glamurnenko.ru/missnewyear/top/?all" class="btn btn-danger btn-lg" style="display: block; margin-bottom: 50px">Показать всех</a>
                        <?
                    }
                    ?>
                </div>
            </div>
        </div>

        <script src="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/ie10-viewport-bug-workaround.js"></script>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/modules/hotornot/poll/jquery.magnific-popup.js"></script>
        
        <script>
        $('.photo-popup-link').magnificPopup({type:'image'});
        $("[data-toggle='tooltip']").tooltip();
        </script>
    </body>
</html>