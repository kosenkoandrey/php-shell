<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Install modules</title>

        <!-- Vendor CSS -->
        <link href="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body>
        <div class="card m-30">
            <div class="card-header">
                <h2>Install imported modules</h2>
            </div>
            <div class="card-body card-padding">
                <?= $data ? 'Error: ' . $data : 'All modules have been installed'; ?>
            </div>
        </div>

        <? APP::Render('core/widgets/page_loader') ?>
        <? APP::Render('core/widgets/ie_warning') ?>

        <!-- Javascript Libraries -->
        <script src="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?=APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2] ?>public/ui/vendors/bower_components/Waves/dist/waves.min.js"></script>

        <? APP::Render('core/widgets/js') ?>
    </body>
</html>