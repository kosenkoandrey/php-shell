<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $data['poll'] ?></title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
        <? APP::Render('core/widgets/template/css_glamurnenko') ?>
    </head>
    <body data-ma-header="teal">
        <section id="main" class="center">
            <section id="content">
                <div class="container">
                    <? APP::Render('core/widgets/template/header', 'include', [
                        $data['poll'] => 'polls/colors/' . APP::Module('Routing')->get['token']
                    ]) ?>
                    <div class="card" style="margin-bottom: 1px;">
                        <div class="card-header"><h2><?= $data['poll'] ?></h2></div>
                        <div class="card-body card-padding">
                            <form method="post" id="step<?= $data['step'] ?>">
                                <?
                                switch ($data['step']) {
                                    case 1:
                                        ?>
                                        <input type="hidden" name="step" value="1">
                                        Напишите top-5 проблем при выборе цвета в одежде и как они влияют на ваш гардероб, настроение и жизнь.
                                        <br><br>
                                        <div class="alert alert-warning" role="alert">
                                            Пожалуйста в ответе будьте максимально подробны, насколько это возможно. 
                                            Напишите больше, чем просто "не знаю, с чем сочетать" или "скучные цвета". 
                                            Чем более детально вы опишите ситуацию, тем более вероятно, что я её помогу решить.
                                        </div>
                                        <textarea class="form-control" name="answers[1]"></textarea>
                                        <?
                                        break;
                                    case 2:
                                        ?>
                                        <input type="hidden" name="step" value="2">
                                        Какой результат вы хотите увидеть после обучения по цвету?
                                        <br><br>
                                        <textarea class="form-control" name="answers[2]"></textarea>
                                        <?
                                        break;
                                }
                                ?>
                                <button type="submit" class="btn palette-Deep-Orange bg waves-effect btn-lg m-t-25">Продолжить</button>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body card-padding">
                            <table border="0" cellpadding="0" cellspacing="0" id="author_block" style="border-collapse: collapse;"><tbody><tr><td width="120" height="120" valign="middle" style="border-collapse: collapse; font-family: Georgia,\'Times New Roman\',serif; font-size: 14px; font-weight: normal; line-height: 20px;padding:0px 0 0 0px"><img class="thumbnail" src="http://www.glamurnenko.ru/images/letter/atF6mpEGhXI.jpg" alt="включите загрузку изображений" width="120" height="120"  style="margin: 0px; padding: 0px; border: 0px none; height: 120px; width:120px; line-height: 100%; outline: medium none; text-decoration: none; display: block;" border="0"></td><td style="border-collapse: collapse; font-family: Georgia,\'Times New Roman\',serif; font-size: 14px; font-weight: normal; line-height: 20px;" width="10"></td><td width="199" align="left" valign="middle" style="border-collapse: collapse; font-family: Georgia,\'Times New Roman\',serif; font-size: 14px; font-weight: normal; line-height: 22px;"><em id="author_signature">Будьте красивы, успешны и ни на кого не похожи!<br /> Екатерина Малярова<br /> имиджмейкер</em></td><td style="border-collapse: collapse; font-family: Georgia,\'Times New Roman\',serif; font-size: 14px; font-weight: normal; line-height: 22px;" width="1"></td><td style="border-collapse: collapse; font-family: Georgia,\'Times New Roman\',serif; font-size: 15px; font-weight: normal; line-height: 20px;" align="left" valign="middle" width="169"><img src="http://www.glamurnenko.ru/images/letter/podpis.png" alt="включите загрузку изображений"  style="margin: 0px; padding: 0px 0px 0 0; border: 0px none; height: 115px; width:169px; line-height: 100%; outline: medium none; text-decoration: none; display: block;" border="0"></td><td style="border-collapse: collapse; font-family: Georgia,\'Times New Roman\',serif; font-size: 14px; font-weight: normal; line-height: 22px;" width="1"></td></tr></tbody></table>
                        </div>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/autosize/dist/autosize.min.js"></script>
        
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                autosize($('[name="answers[1]"]'));
                autosize($('[name="answers[2]"]'));
                
                $('#step1').submit(function() {
                    var a1 = $(this).find('[name="answers[1]"]');

                    a1.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (a1.val() === '') { 
                        swal({
                            title: 'Напишите top-5 проблем при выборе цвета в одежде и как они влияют на ваш гардероб, настроение и жизнь.',
                            type: 'error',
                            timer: 4000,
                            showConfirmButton: false
                        });
                        return false; 
                    }

                    $(this).find('[type="submit"]').html('Подождите...').attr('disabled', true);
                });
                
                $('#step2').submit(function() {
                    var a2 = $(this).find('[name="answers[2]"]');

                    a2.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (a2.val() === '') { 
                        swal({
                            title: 'Какой результат вы хотите увидеть после обучения по цвету?',
                            type: 'error',
                            timer: 4000,
                            showConfirmButton: false
                        });
                        return false; 
                    }

                    $(this).find('[type="submit"]').html('Подождите...').attr('disabled', true);
                });
            });
        </script>
    </body>
</html>