<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Mail</title>

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
            'Mail settings' => 'admin/mail/settings'
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
                                    <li role="presentation"><a href="#settings-transport" role="tab" data-toggle="tab">Transport</a></li>
                                    <li role="presentation"><a href="#settings-copies" role="tab" data-toggle="tab">Copies of sent emails</a></li>
                                    <li role="presentation"><a href="#settings-fbl" role="tab" data-toggle="tab">FBL reports</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active animated fadeIn in" id="settings-main">
                                        <div class="form-group">
                                            <label for="module_mail_db_connection" class="col-sm-2 control-label">DB connection</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <select id="module_mail_db_connection" name="module_mail_db_connection" class="selectpicker">
                                                        <? foreach (array_keys(APP::Module('DB')->conf['connections']) as $connection) { ?><option value="<?= $connection ?>"><?= $connection ?></option><? } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_mail_tmp_dir" class="col-sm-2 control-label">Temp dir</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_mail_tmp_dir" id="module_mail_tmp_dir">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="settings-transport">
                                        <div class="form-group">
                                            <label for="module_mail_x_mailer" class="col-sm-2 control-label">X-Mailer</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_mail_x_mailer" id="module_mail_x_mailer">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_mail_charset" class="col-sm-2 control-label">Charset</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_mail_charset" id="module_mail_charset">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="settings-copies">
                                        <div class="form-group">
                                            <label for="module_mail_save_sent_email" class="col-sm-2 control-label">Save</label>
                                            <div class="col-sm-1">
                                                <div class="toggle-switch m-t-10">
                                                    <input id="module_mail_save_sent_email" name="module_mail_save_sent_email" type="checkbox" hidden="hidden">
                                                    <label for="module_mail_save_sent_email" class="ts-helper"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_mail_sent_email_lifetime" class="col-sm-2 control-label">Lifetime</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_mail_sent_email_lifetime" id="module_mail_sent_email_lifetime">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="settings-fbl">
                                        <div class="form-group">
                                            <label for="module_mail_fbl_server" class="col-sm-2 control-label">Server</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_mail_fbl_server" id="module_mail_fbl_server">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_mail_fbl_login" class="col-sm-2 control-label">Login</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_mail_fbl_login" id="module_mail_fbl_login">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_mail_fbl_password" class="col-sm-2 control-label">Password</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="password" class="form-control" name="module_mail_fbl_password" id="module_mail_fbl_password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_mail_fbl_prefix" class="col-sm-2 control-label">Prefix</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_mail_fbl_prefix" id="module_mail_fbl_prefix">
                                                </div>
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
                $('#module_mail_db_connection').val('<?= APP::Module('Mail')->settings['module_mail_db_connection'] ?>');
                $('#module_mail_tmp_dir').val('<?= APP::Module('Mail')->settings['module_mail_tmp_dir'] ?>');
                $('#module_mail_x_mailer').val('<?= APP::Module('Mail')->settings['module_mail_x_mailer'] ?>');
                $('#module_mail_charset').val('<?= APP::Module('Mail')->settings['module_mail_charset'] ?>');
                $('#module_mail_save_sent_email').prop('checked', <?= (int) APP::Module('Mail')->settings['module_mail_save_sent_email'] ?>);
                $('#module_mail_sent_email_lifetime').val('<?= APP::Module('Mail')->settings['module_mail_sent_email_lifetime'] ?>');
                $('#module_mail_fbl_server').val('<?= APP::Module('Mail')->settings['module_mail_fbl_server'] ?>');
                $('#module_mail_fbl_login').val('<?= APP::Module('Mail')->settings['module_mail_fbl_login'] ?>');
                $('#module_mail_fbl_password').val('<?= APP::Module('Mail')->settings['module_mail_fbl_password'] ?>');
                $('#module_mail_fbl_prefix').val('<?= APP::Module('Mail')->settings['module_mail_fbl_prefix'] ?>');

                $('#update-settings').submit(function(event) {
                    event.preventDefault();

                    var module_mail_db_connection = $(this).find('#module_mail_db_connection');
                    var module_mail_tmp_dir = $(this).find('#module_mail_tmp_dir');
                    var module_mail_x_mailer = $(this).find('#module_mail_x_mailer');
                    var module_mail_charset = $(this).find('#module_mail_charset');
                    var module_mail_sent_email_lifetime = $(this).find('#module_mail_sent_email_lifetime');
                    var module_mail_fbl_server = $(this).find('#module_mail_fbl_server');
                    var module_mail_fbl_login = $(this).find('#module_mail_fbl_login');
                    var module_mail_fbl_password = $(this).find('#module_mail_fbl_password');
                    var module_mail_fbl_prefix = $(this).find('#module_mail_fbl_prefix');

                    module_mail_db_connection.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_mail_tmp_dir.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_mail_x_mailer.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_mail_charset.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_mail_sent_email_lifetime.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_mail_fbl_server.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_mail_fbl_login.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_mail_fbl_password.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    module_mail_fbl_prefix.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    
                    if (module_mail_db_connection.val() === '') { module_mail_db_connection.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_mail_tmp_dir.val() === '') { module_mail_tmp_dir.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_mail_x_mailer.val() === '') { module_mail_x_mailer.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_mail_charset.val() === '') { module_mail_charset.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_mail_sent_email_lifetime.val() === '') { module_mail_sent_email_lifetime.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; } 
                    if (module_mail_fbl_server.val() === '') { module_mail_fbl_server.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_mail_fbl_login.val() === '') { module_mail_fbl_login.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_mail_fbl_password.val() === '') { module_mail_fbl_password.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (module_mail_fbl_prefix.val() === '') { module_mail_fbl_prefix.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    
                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/mail/api/settings/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Mail settings has been updated',
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