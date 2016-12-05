<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Costs</title>

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
            'Costs settings' => 'admin/costs/settings'
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
                                <ul class="tab-nav m-b-15" role="tablist" data-tab-color="teal">
                                    <li class="active"><a href="#settings-main" role="tab" data-toggle="tab">Main</a></li>
                                    <li role="presentation"><a href="#settings-yandex" role="tab" data-toggle="tab">Yandex.Direct</a></li>
                                    <li role="presentation"><a href="#settings-facebook" role="tab" data-toggle="tab">Facebook</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active animated fadeIn in" id="settings-main">
                                        <div class="form-group">
                                            <label for="module_costs_db_connection" class="col-sm-2 control-label">DB connection</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <select id="module_costs_db_connection" name="module_costs_db_connection" class="selectpicker">
                                                        <? foreach (array_keys(APP::Module('DB')->conf['connections']) as $connection) { ?><option value="<?= $connection ?>"><?= $connection ?></option><? } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_costs_tmp_dir" class="col-sm-2 control-label">Tmp dir</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_costs_tmp_dir" id="module_costs_tmp_dir">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_costs_max_execution_time" class="col-sm-2 control-label">Max execution time</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_costs_max_execution_time" id="module_costs_max_execution_time">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="settings-yandex">
                                        <div class="form-group">
                                            <label for="module_costs_yandex_utm_source" class="col-sm-2 control-label">UTM-source</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_costs_yandex_utm_source" id="module_costs_yandex_utm_source">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_costs_yandex_utm_medium" class="col-sm-2 control-label">UTM-medium</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_costs_yandex_utm_medium" id="module_costs_yandex_utm_medium">
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="margin: 50px 0 40px 0;">
                                        <div class="form-group">
                                            <label for="module_costs_yandex_client_id" class="col-sm-2 control-label">Client ID</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_costs_yandex_client_id" id="module_costs_yandex_client_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_costs_yandex_client_secret" class="col-sm-2 control-label">Client secret</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_costs_yandex_client_secret" id="module_costs_yandex_client_secret">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_costs_yandex_token" class="col-sm-2 control-label">Token</label>
                                            <?
                                            if (APP::Module('Costs')->settings['module_costs_yandex_token']) {
                                                ?>
                                                <div class="col-sm-3">
                                                    <div class="fg-line">
                                                        <input type="text" class="form-control" name="module_costs_yandex_token" id="module_costs_yandex_token" disabled="disabled">
                                                    </div>
                                                </div>
                                                <?
                                            }
                                            ?>
                                            <div class="col-sm-3">
                                                <a class="btn palette-Orange bg waves-effect btn-sm" href="<?= APP::Module('Routing')->root ?>admin/costs/yandex/token"><i class="zmdi zmdi-key"></i> Get access token</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="settings-facebook">
                                        <div class="form-group">
                                            <label for="module_costs_facebook_client_id" class="col-sm-2 control-label">Client ID</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_costs_facebook_client_id" id="module_costs_facebook_client_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_costs_facebook_client_secret" class="col-sm-2 control-label">Client secret</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_costs_facebook_client_secret" id="module_costs_facebook_client_secret">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_costs_facebook_token" class="col-sm-2 control-label">Token</label>
                                            <?
                                            if (APP::Module('Costs')->settings['module_costs_facebook_token']) {
                                                ?>
                                                <div class="col-sm-3">
                                                    <div class="fg-line">
                                                        <input type="text" class="form-control" name="module_costs_facebook_token" id="module_costs_facebook_token" disabled="disabled">
                                                    </div>
                                                </div>
                                                <?
                                            }
                                            ?>
                                            <div class="col-sm-3">
                                                <a class="btn palette-Orange bg waves-effect btn-sm" href="<?= APP::Module('Routing')->root ?>admin/costs/yandex/token"><i class="zmdi zmdi-key"></i> Get access token</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-5">
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
                <?
                if (isset(APP::Module('Routing')->get['yandex_token'])) {
                    ?>
                    $( window ).load(function() {
                        <?
                        switch (APP::Module('Routing')->get['yandex_token']) {
                            case 'success':
                                ?>
                                swal({
                                    title: 'Done!',
                                    text: 'Yandex access token has been saved',
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Ok',
                                    closeOnConfirm: true
                                });
                                <?
                                break;
                            case 'error':
                                ?>
                                swal({
                                    title: 'Error!',
                                    text: 'Yandex access token has not been saved',
                                    type: 'error',
                                    showCancelButton: false,
                                    confirmButtonText: 'Ok',
                                    closeOnConfirm: true
                                });
                                <?
                                break;
                        }
                        ?>
                    });
                    <?
                }
                ?>
                
                $('#module_costs_db_connection').val('<?= APP::Module('Costs')->settings['module_costs_db_connection'] ?>');
                $('#module_costs_tmp_dir').val('<?= APP::Module('Costs')->settings['module_costs_tmp_dir'] ?>');
                $('#module_costs_max_execution_time').val('<?= APP::Module('Costs')->settings['module_costs_max_execution_time'] ?>');
                $('#module_costs_yandex_utm_source').val('<?= APP::Module('Costs')->settings['module_costs_yandex_utm_source'] ?>');
                $('#module_costs_yandex_utm_medium').val('<?= APP::Module('Costs')->settings['module_costs_yandex_utm_medium'] ?>');
                $('#module_costs_yandex_client_id').val('<?= APP::Module('Costs')->settings['module_costs_yandex_client_id'] ?>');
                $('#module_costs_yandex_client_secret').val('<?= APP::Module('Costs')->settings['module_costs_yandex_client_secret'] ?>');
                $('#module_costs_yandex_token').val('<?= APP::Module('Costs')->settings['module_costs_yandex_token'] ?>');
                
                $('#module_costs_facebook_client_id').val('<?= APP::Module('Costs')->settings['module_costs_facebook_client_id'] ?>');
                $('#module_costs_facebook_client_secret').val('<?= APP::Module('Costs')->settings['module_costs_facebook_client_secret'] ?>');
                $('#module_costs_facebook_token').val('<?= APP::Module('Costs')->settings['module_costs_facebook_token'] ?>');

                $('#update-settings').submit(function(event) {
                    event.preventDefault();

                    var module_costs_db_connection = $(this).find('#module_costs_db_connection');
                    var module_costs_tmp_dir = $(this).find('#module_costs_db_connection');
                    var module_costs_max_execution_time = $(this).find('#module_costs_max_execution_time');
                    var module_costs_yandex_utm_source = $(this).find('#module_costs_yandex_utm_source');
                    var module_costs_yandex_utm_medium = $(this).find('#module_costs_yandex_utm_medium');
                    var module_costs_yandex_client_id = $(this).find('#module_costs_yandex_client_id');
                    var module_costs_yandex_client_secret = $(this).find('#module_costs_yandex_client_secret');
                    var module_costs_facebook_client_id = $(this).find('#module_costs_facebook_client_id');
                    var module_costs_facebook_client_secret = $(this).find('#module_costs_yandex_client_secret');

                    module_costs_db_connection.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_costs_tmp_dir.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_costs_max_execution_time.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_costs_yandex_utm_source.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_costs_yandex_utm_medium.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_costs_yandex_client_id.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_costs_yandex_client_secret.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_costs_facebook_client_id.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_costs_facebook_client_secret.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    
                    if (module_costs_db_connection.val() === '') { module_costs_db_connection.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_costs_tmp_dir.val() === '') { module_costs_tmp_dir.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_costs_max_execution_time.val() === '') { module_costs_max_execution_time.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_costs_yandex_utm_source.val() === '') { module_costs_yandex_utm_source.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_costs_yandex_utm_medium.val() === '') { module_costs_yandex_utm_medium.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_costs_yandex_client_id.val() === '') { module_costs_yandex_client_id.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_costs_yandex_client_secret.val() === '') { module_costs_yandex_client_secret.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_costs_facebook_client_id.val() === '') { module_costs_facebook_client_id.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_costs_facebook_client_secret.val() === '') { module_costs_facebook_client_secret.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    
                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/costs/api/settings/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Costs settings has been updated',
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