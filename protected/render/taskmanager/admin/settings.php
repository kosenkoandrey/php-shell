<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Task Manager</title>

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
            'Task Manager' => 'admin/taskmanager'
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
                                    <label for="module_taskmanager_db_connection" class="col-sm-2 control-label">Database connection</label>
                                    <div class="col-sm-2">
                                        <div class="fg-line">
                                            <select id="module_taskmanager_db_connection" name="module_taskmanager_db_connection" class="selectpicker">
                                                <? foreach (array_keys(APP::Module('DB')->conf['connections']) as $connection) { ?><option value="<?= $connection ?>"><?= $connection ?></option><? } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module_taskmanager_complete_lifetime" class="col-sm-2 control-label">Complete task lifetime</label>
                                    <div class="col-sm-2">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="module_taskmanager_complete_lifetime" id="module_taskmanager_complete_lifetime">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module_taskmanager_max_execution_time" class="col-sm-2 control-label">Max execution time</label>
                                    <div class="col-sm-1">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="module_taskmanager_max_execution_time" id="module_taskmanager_max_execution_time">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module_taskmanager_memory_limit" class="col-sm-2 control-label">Memory limit</label>
                                    <div class="col-sm-1">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="module_taskmanager_memory_limit" id="module_taskmanager_memory_limit">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module_taskmanager_tmp_dir" class="col-sm-2 control-label">Temp dir</label>
                                    <div class="col-sm-2">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="module_taskmanager_tmp_dir" id="module_taskmanager_tmp_dir">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-2">
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
                $('#module_taskmanager_db_connection').val('<?= $data['module_taskmanager_db_connection'] ?>');
                $('#module_taskmanager_complete_lifetime').val('<?= $data['module_taskmanager_complete_lifetime'] ?>');
                $('#module_taskmanager_max_execution_time').val('<?= $data['module_taskmanager_max_execution_time'] ?>');
                $('#module_taskmanager_memory_limit').val('<?= $data['module_taskmanager_memory_limit'] ?>');
                $('#module_taskmanager_tmp_dir').val('<?= $data['module_taskmanager_tmp_dir'] ?>');

                $('#update-settings').submit(function(event) {
                    event.preventDefault();

                    var module_taskmanager_db_connection = $(this).find('#module_taskmanager_db_connection');
                    var module_taskmanager_complete_lifetime = $(this).find('#module_taskmanager_complete_lifetime');
                    var module_taskmanager_max_execution_time = $(this).find('#module_taskmanager_max_execution_time');
                    var module_taskmanager_memory_limit = $(this).find('#module_taskmanager_memory_limit');
                    var module_taskmanager_tmp_dir = $(this).find('#module_taskmanager_tmp_dir');
                    
                    module_taskmanager_db_connection.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_taskmanager_complete_lifetime.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_taskmanager_max_execution_time.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_taskmanager_memory_limit.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_taskmanager_tmp_dir.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    
                    if (module_taskmanager_db_connection.val() === '') { module_taskmanager_db_connection.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_taskmanager_complete_lifetime.val() === '') { module_taskmanager_complete_lifetime.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_taskmanager_max_execution_time.val() === '') { module_taskmanager_max_execution_time.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-1').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_taskmanager_memory_limit.val() === '') { module_taskmanager_memory_limit.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-1').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_taskmanager_tmp_dir.val() === '') { module_taskmanager_tmp_dir.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    
                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/taskmanager/api/settings/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Task manager settings has been updated',
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