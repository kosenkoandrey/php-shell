<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Activate</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body>
        <div class="login" data-lbg="teal">
            <div class="l-block toggled" id="l-lockscreen">
                <div class="lb-header palette-Teal bg">
                    <?
                    switch ($data) {
                        case 'success': ?><i class="zmdi zmdi-check-circle"></i>Account activation successful<? break;
                        case 'error': ?><i class="zmdi zmdi-close-circle"></i>Account activation failed<? break;
                    }
                    ?>
                    
                </div>
                <?
                switch ($data) {
                    case 'success': ?><a href="<?= APP::Module('Routing')->root ?>users/actions/login" class="btn btn-default btn-lg btn-block waves-effect">Sign in</a><? break;
                    case 'error': ?><div class="lb-body">Check activation link</div><? break;
                }
                ?>
            </div>
        </div>
        
        <? APP::Render('core/widgets/page_loader') ?>
        <? APP::Render('core/widgets/ie_warning') ?>

        <!-- Javascript Libraries -->
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/Waves/dist/waves.min.js"></script>
        
        <? APP::Render('core/widgets/js') ?>
    </body>
</html>