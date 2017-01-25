<?
$nav = [];
$nav_cnt = 0;

foreach ($data['path'] as $key => $value) {
    ++ $nav_cnt;
    
    if ($key) {
        if (count($data['path']) !== $nav_cnt) {
            $nav[$value] = 'members/pages/' . APP::Module('Crypt')->Encode($key);
        } else {
            $nav[$value] = mb_substr(APP::Module('Routing')->RequestURI(), 1);
        }
    }
    
}
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Платные материалы</title>

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
                    <? APP::Render('core/widgets/template/header', 'include', $nav) ?>
                    <div class="card">
                        <div class="card-body">
                            <?
                            if (count($data['list'])) {
                                ?>
                                <table class="table table-hover table-vmiddle">
                                    <tbody>
                                        <?
                                        foreach ($data['list'] as $item) {
                                            switch ($item[0]) {
                                                case 'group':
                                                    ?>
                                                    <tr>
                                                        <td style="font-size: 16px"><span style="display: inline-block" class="avatar-char palette-Teal bg m-r-5"><i class="zmdi zmdi-folder"></i></span> <a style="color: #4C4C4C" href="<?= APP::Module('Routing')->root ?>members/pages/<?= APP::Module('Crypt')->Encode($item[1]) ?>"><?= $item[2] ?></a></td>
                                                    </tr>
                                                    <?
                                                    break;
                                                case 'page':
                                                    ?>
                                                    <tr>
                                                        <td style="font-size: 16px;"><span style="display: inline-block" class="avatar-char palette-Orange-400 bg m-r-5"><i class="zmdi zmdi-file"></i></span> <a style="color: #4C4C4C" href="<?= APP::Module('Routing')->root ?>members/page/<?= APP::Module('Crypt')->Encode($item[1]) ?>" target="_blank"><?= $item[2] ?></a></td>
                                                    </tr>
                                                    <?
                                                    break;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?
                            } else {
                                ?>
                                <div class="card-body card-padding">
                                    <div class="alert alert-warning m-b-0" role="alert"><i class="zmdi zmdi-close-circle"></i> Pages not found</div>
                                </div>
                                <?
                            }
                            ?>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>

        <? APP::Render('core/widgets/js') ?>
    </body>
</html>