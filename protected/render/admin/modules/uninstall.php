<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Uninstall modules</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Uninstall module ' . $data[0] => 'admin/modules/uninstall/' . APP::Module('Routing')->get['module_hash']
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-body card-padding">
                            <?
                            if (count($data[1])) {
                                ?>
                                Module <b><?= $data[0] ?></b> is used in the modules:
                                <ul>
                                    <?
                                    foreach ($data[1] as $value) {
                                        ?><li><?= $value ?></li><?
                                    }
                                    ?>
                                </ul>
                                Are you sure want to delete all these modules?
                                <?
                            } else {
                                ?>Are you sure want to delete <b><?= $data[0] ?></b> module?<?
                            }
                            ?>
                            <br><br>
                            <button id="confirm-uninstall" class="btn palette-Teal bg waves-effect btn-lg">Uninstall</button>
                        </div>
                    </div>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        
        <script>
            $('#confirm-uninstall').on('click', function() {
                swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this modules',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm){
                    if (isConfirm) {
                        $.post('<?= APP::Module('Routing')->root ?>admin/api/modules/uninstall/<?= APP::Module('Crypt')->Encode($data[0]) ?>', function() {
                            swal({
                                title: 'Done!',
                                text: 'Modules has been removed',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: false
                            }, function(){
                                window.location.href = '<?= APP::Module('Routing')->root ?>admin';
                            });
                        });
                    }
                });
            });
        </script>
        
        <? APP::Render('core/widgets/js') ?>
    </body>
</html>