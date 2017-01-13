<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Preview page</title>

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
            $data['page']['title'] => 'members/page/' . APP::Module('Routing')->get['page_id_hash']
        ]) ?>
        <section id="main" class="center">
            <section id="content">
                <div class="container">
                    <?
                    preg_match_all('/\[file-([0-9]+)]/i', $data['page']['content'], $files_out);
                    
                    foreach ($files_out[1] as $key => $value) {
                        $file_data = APP::Module('DB')->Select(
                            APP::Module('Files')->settings['module_files_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                            ['title', 'type'], 'files', 
                            [['id', '=', $value, PDO::PARAM_INT]]
                        ); 
                        
                        $file_html = '';
                        
                        switch ($file_data['type']) {
                            case 'video/mp4':
                                $file_html = '<video width="640" height="480" controls><source src="' . APP::Module('Routing')->root . 'files/download/' . APP::Module('Crypt')->Encode($value) . '" type="video/mp4"></video><hr><a class="btn btn-lg palette-Teal bg waves-effect" target="_blank" href="' . APP::Module('Routing')->root . 'files/download/' . APP::Module('Crypt')->Encode($value) .'" target="_blank"><i class="zmdi zmdi-download"></i> Download</a>';
                                break;
                            case 'application/pdf':
                                $file_html = '<p>Preview is not supported</p><hr><a class="btn btn-lg palette-Teal bg waves-effect" target="_blank" href="' . APP::Module('Routing')->root . 'files/download/' . APP::Module('Crypt')->Encode($value) .'" target="_blank"><i class="zmdi zmdi-download"></i> Download</a>';
                                break;
                            case 'image/jpeg':
                                $file_html = '<img src="' . APP::Module('Routing')->root . 'files/download/' . APP::Module('Crypt')->Encode($value) . '"><hr><a class="btn btn-lg palette-Teal bg waves-effect" target="_blank" href="' . APP::Module('Routing')->root . 'files/download/' . APP::Module('Crypt')->Encode($value) .'" target="_blank"><i class="zmdi zmdi-download"></i> Download</a>';
                                break;
                        }
                        
                        $data['page']['content'] = str_replace($files_out[0][$key], $file_html, $data['page']['content']);
                    }
                    
                    echo eval('?>' . $data['page']['content'] . '<?');
                    ?>
                </div>
                
                <div class="card">
                    <?
                    if (isset(APP::$modules['Comments'])) {
                        $comment_object_type = APP::Module('DB')->Select(APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchColumn', 0], ['id'], 'comments_objects', [['name', '=', "MemberPage", PDO::PARAM_STR]]);

                        APP::Render('comments/widgets/default/list', 'include', [
                            'type' => $comment_object_type,
                            'id' => APP::Module('Crypt')->Decode(APP::Module('Routing')->get['page_id_hash']),
                            'likes' => true,
                            'class' => [
                                'holder' => 'palette-Grey-50 bg p-l-10'
                            ]
                        ]);

                        APP::Render('comments/widgets/default/form', 'include', [
                            'type' => $comment_object_type,
                            'id' => APP::Module('Crypt')->Decode(APP::Module('Routing')->get['page_id_hash']),
                            'login' => [],
                            'class' => [
                                'holder' => false,
                                'list' => 'palette-Grey-50 bg p-l-10'
                            ]
                        ]);
                    }
                    ?>
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