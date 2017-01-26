<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Бизнес монитор</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Бизнес монитор' => 'admin'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <?
                    foreach ($data['cards'] as $key => $value) {
                        ?>
                        <div class="card">
                            <?
                            switch ($value['type']) {
                                case 'card':
                                    ?>
                                    <div class="card-header">
                                        <h2><?= $key ?></h2>
                                    </div>
                                    <div class="card-body card-padding" id="card-<?= md5($key) ?>">
                                        <?= $value['data'][0] ?>
                                    </div>
                                    <?
                                    ob_start();
                                    echo $value['data'][1];
                                    APP::$insert['dashboard_css_card_' . md5($key)] = ['css', 'code', 'before', '</body>', ob_get_contents()];
                                    ob_end_clean();

                                    ob_start();
                                    echo $value['data'][2];
                                    APP::$insert['dashboard_js_card_' . md5($key)] = ['js', 'code', 'before', '</body>', ob_get_contents()];
                                    ob_end_clean();
                                    break;
                                case 'tab':
                                    $first_tab_hash = md5($key . key($value['data']));
                                    ?>
                                    <ul class="tab-nav tab-nav-right" role="tablist" data-tab-color="teal" style="padding: 15px 30px 0 30px;">
                                        <li class="pull-left"><h4><?= $key ?></h4></li>
                                        <?
                                        foreach ($value['data'] as $tab => $tab_data) {
                                            ?>
                                            <li id="tab-nav-<?= md5($key . $tab) ?>" role="presentation"><a href="#tab-<?= md5($key . $tab) ?>" role="tab" data-toggle="tab"><?= $tab ?></a></li>
                                            <?
                                        }
                                        ?>
                                    </ul>
                                    <div class="card-body card-padding">
                                        <div class="tab-content" style="padding: 0;">
                                            <?
                                            foreach ($value['data'] as $tab => $tab_data) {
                                                ?>
                                                <div role="tabpanel" class="tab-pane animated fadeIn" id="tab-<?= md5($key . $tab) ?>">
                                                    <?= $tab_data[0] ?>
                                                </div>
                                                <?
                                                ob_start();
                                                echo $tab_data[1];
                                                APP::$insert['dashboard_css_tab_' . md5($key . $tab)] = ['css', 'code', 'before', '</body>', ob_get_contents()];
                                                ob_end_clean();
                                                
                                                ob_start();
                                                echo $tab_data[2];
                                                APP::$insert['dashboard_js_tab_' . md5($key . $tab)] = ['js', 'code', 'before', '</body>', ob_get_contents()];
                                                ob_end_clean();
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?
                                    ob_start();
                                    ?>
                                    <script>$('#tab-nav-<?= $first_tab_hash ?> > a').trigger('click');</script>
                                    <?
                                    APP::$insert['dashboard_js_tab_click_' . $first_tab_hash] = ['js', 'code', 'before', '</body>', ob_get_contents()];
                                    ob_end_clean();
                                    break;
                            }
                            ?>
                        </div>
                        <?
                    }
                    ?>
                </div>
            </section>

            <? APP::Render('admin/widgets/footer') ?>
        </section>

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