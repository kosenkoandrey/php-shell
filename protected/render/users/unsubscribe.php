<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Unsubscribe</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        <? APP::Render('core/widgets/css') ?>
        <? APP::Render('core/widgets/template/css') ?>
        <style>
            .period-block span{
                vertical-align: top;
            }
        </style>
    </head>
    <body data-ma-header="teal">
        <? APP::Render('admin/widgets/header', 'include', [
            'Unsubscribe' => 'users/unsubscribe/' . APP::Module('Routing')->get['mail_log_hash']
        ]) ?>
        <section id="main" class="center">
            <section id="content">
                <div class="container">
                    <div class="card">
                    <? if($data['error']) { ?>
                        <div class="card-header"><h2>Неверная ссылка отписки</h2></div>
                        <div class="card-body card-padding">
                        Если вы уверены, что прошли по правильной ссылке, пожалуйста, напишите на <a href="mailto:support@glamurnenko.ru">support@glamurnenko.ru</a> и приложите вашу ссылку.
                        <br><br>
                        А пока вы ждете ответа от нашей службы поддержки, посмотрите наш сайт:
                        <br>
                        <a href="http://glamurnenko.ru">http://glamurnenko.ru</a>
                        </div>
                    <? }else{ 
                        if($data['active']){ ?>
                            <div class="card-header"><h2>Вы хотите отписаться?</h2></div>
                            <div class="card-body card-padding">
                                <div class="period-block">
                                    <span>
                                        <label class="radio radio-inline m-r-5">
                                            <input type="radio" name="period" value="period" checked>
                                            <i class="input-helper"></i>
                                            <span>
                                                Приостановить рассылку на
                                            </span>
                                            <span style="width: 80px;display: inline-block;" class="m-r-10 m-l-10">
                                                <select class="selectpicker" name="time">
                                                    <option value="+1 week">1</option>
                                                    <option value="+2 week">2</option>
                                                    <option value="+3 week">3</option>
                                                    <option value="+4 week">4</option>
                                                </select>
                                            </span>
                                            <span>
                                                недель.
                                                Вы не будете получать рассылки в течении этого времени.
                                            </span>
                                        </label>
                                    </span>
                                </div>
                                <div style="margin-top:10px;" class="radio">
                                    <label>
                                        <input type="radio" name="period" value="unsubscribe">
                                        <i class="input-helper"></i>
                                        Да, отписаться
                                    </label>
                                </div>
                                <div style="margin-top: 20px;"><button type="button" class="btn-unsubscribe btn palette-Teal bg waves-effect btn-lg">Продолжить</button></div>
                            </div>
                    <?  }else{ ?>
                        <div class="card-header"><h2>Вы уже отписаны от рассылок</h2></div>
                    <? 
                        }   
                    } 
                    ?>
                    </div>
                </div>
            </section>

            <? APP::Render('core/widgets/template/footer') ?>
        </section>

        <? APP::Render('core/widgets/page_loader') ?>
        <? APP::Render('core/widgets/ie_warning') ?>

        <!-- Javascript Libraries -->
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/moment/min/moment.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/input-mask/input-mask.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(function(){
               $(document).on('click', '.btn-unsubscribe', function(){
                    var data = {
                        'mail_log' : '<?= APP::Module('Routing')->get['mail_log_hash']; ?>',
                        'timeout'  : $('select[name="time"]').val()
                    };
                    var url = '';
                    var message = '';
                    
                    if($('input[name="period"]:checked').val() == 'unsubscribe'){
                        url = '<?= APP::Module('Routing')->root ?>users/api/unsubscribe.json';
                        message = 'Вы успешно отписались от рассылки';
                    }else{
                        url = '<?= APP::Module('Routing')->root ?>users/api/pause.json';
                        message = 'Вы успешно приостановили рассылку';
                    }
                    
                    $('.card-body').html('<center><div class="preloader pl-xxl"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20" /></svg></div></center>');
                    
                    $.post(url, data, function(result){
                        if(result.status == 'success'){
                            $('.card-header h2').html(message);
                            $('.card-body').remove();
                        }
                    });   
               });
            });
        </script>
    </body>
</html>