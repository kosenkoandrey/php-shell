<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Preview file</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
        <? APP::Render('core/widgets/template/css') ?>
    </head>
    <body data-ma-header="teal">
        <? APP::Render('admin/widgets/header', 'include', [
            $data['file']['title'] => 'admin/files/preview' . APP::Module('Routing')->get['file_id_hash']
        ]) ?>
        <section id="main" class="center">
            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-body card-padding">
                            <?
                            switch ($data['file']['type']) {
                                case 'video/mp4':
                                    ?>
                                    <video width="640" height="480" controls>
                                        <source src="<?= APP::Module('Routing')->root ?>files/download/<?= APP::Module('Crypt')->Encode($data['file']['id']) ?>" type="video/mp4">
                                    </video>
                                    <?
                                    break;
                                case 'application/pdf':
                                    ?>Preview is not supported<?
                                    break;
                                case 'image/jpeg':
                                    ?><img src="<?= APP::Module('Routing')->root ?>files/download/<?= APP::Module('Crypt')->Encode($data['file']['id']) ?>"><?
                                    break;
                            }
                            ?>
                            <hr>
                            <a class="btn btn-lg palette-Teal bg waves-effect" target="_blank" href="<?= APP::Module('Routing')->root ?>files/download/<?= APP::Module('Crypt')->Encode($data['file']['id']) ?>" target="_blank"><i class="zmdi zmdi-download"></i> Download</a>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2>Embed on the member pages</h2>
                        </div>
                        <div class="card-body card-padding">
                            <h2>[file-<?= $data['file']['id'] ?>]</h2>
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

        <? APP::Render('core/widgets/js') ?>
    </body>
</html>