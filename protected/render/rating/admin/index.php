<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - SendThis</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'SendThis' => 'admin/sendthis'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="update-settings" class="form-horizontal" role="form">
                            <div class="card-header">
                                <h2>Settings</h2>
                            </div>
                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="module_sendthis_version" class="col-sm-1 control-label">Version</label>
                                    <div class="col-sm-1">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="module_sendthis_version" id="module_sendthis_version">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module_sendthis_key" class="col-sm-1 control-label">Key</label>
                                    <div class="col-sm-5">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="module_sendthis_key" id="module_sendthis_key">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module_sendthis_tmp_dir" class="col-sm-1 control-label">Temp dir</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="module_sendthis_tmp_dir" id="module_sendthis_tmp_dir">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-5">
                                        <button type="submit" class="btn palette-Teal bg waves-effect btn-lg">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                $('#module_sendthis_version').val('<?= APP::Module('SendThis')->settings['module_sendthis_version'] ?>');
                $('#module_sendthis_key').val('<?= APP::Module('SendThis')->settings['module_sendthis_key'] ?>');
                $('#module_sendthis_tmp_dir').val('<?= APP::Module('SendThis')->settings['module_sendthis_tmp_dir'] ?>');

                $('#update-settings').submit(function(event) {
                    event.preventDefault();

                    var module_sendthis_version = $(this).find('#module_sendthis_version');
                    var module_sendthis_key = $(this).find('#module_sendthis_key');
                    var module_sendthis_tmp_dir = $(this).find('#module_sendthis_tmp_dir');
                    
                    module_sendthis_version.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_sendthis_key.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_sendthis_tmp_dir.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    
                    if (module_sendthis_version.val() === '') { module_sendthis_version.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-1').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_sendthis_key.val() === '') { module_sendthis_key.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-5').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_sendthis_tmp_dir.val() === '') { module_sendthis_tmp_dir.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    
                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/sendthis/api/settings/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'SendThis settings has been updated',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: true
                                    });
                                    break;
                                case 'error': 
                                    $.each(result.errors, function(i, error) {});
                                    break;
                            }

                            $('#update-settings').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
</html>