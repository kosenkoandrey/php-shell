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
            'Import local modules' => 'admin/modules/import'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <?
                        $imported_modules = glob(ROOT . '/protected/import/*.zip');

                        if (count($imported_modules)) {
                            ?>
                            <div class="card-header">
                                <h2>Imported modules</h2>
                            </div>
                            <div class="card-body card-padding">
                                <table class="table m-b-10">
                                    <tbody>
                                        <?
                                        foreach ($imported_modules as $module) {
                                            ?>
                                            <tr>
                                                <td><?= basename($module, '.zip') ?></td>
                                                <td><a href="<?= APP::Module('Routing')->root ?>admin/modules/import/remove/<?= APP::Module('Crypt')->Encode($module) ?>">Remove</a></td>
                                            </tr>
                                            <?
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <a href="<?= APP::Module('Routing')->root ?>admin/modules/import/install" class="btn palette-Teal bg waves-effect btn-lg">Install</a>
                            </div>
                            <?
                        }
                        ?>

                        <div class="card-header">
                            <h2>Import modules</h2>
                        </div>
                        <div class="card-body card-padding">
                            <form id="import" action="<?= APP::Module('Routing')->SelfURL() ?>" method="post" enctype="multipart/form-data"class="form-horizontal" role="form">
                                <div id="new-modules"></div>
                                <a href="javascript:void(0)" id="add-file" class="btn btn-default btn-sm">Add file</a>
                                <br><br>
                                <button type="submit" class="btn palette-Teal bg waves-effect btn-lg">Import</button>
                            </form>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/fileinput/fileinput.min.js"></script>

        <script>
            $('#import > #add-file').on('click', function() {
                $('#new-modules').append('<div class="form-group"><div class="module"><div class="col-sm-3"><div class="fileinput fileinput-new" data-provides="fileinput"><span class="btn btn-default btn-sm waves-effect btn-file m-r-10"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="modules[]"></span><span class="fileinput-filename"></span><a href="#" class="close fileinput-exists" data-dismiss="fileinput">&times;</a></div></div><div class="col-sm-1"><a href="javascript:void(0)" class="remove">Remove</a></div></div></div>');
            });

            $(document).on('click', '#import .module .remove', function(event) {
                $(this).closest('.form-group').remove();
            });
        </script>
        
        <? APP::Render('core/widgets/js') ?>
    </body>
</html>