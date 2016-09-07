<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Users</title>

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
            'Users' => 'admin/users'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="update-services" class="form-horizontal" role="form">
                            <div class="card-header">
                                <h2>Services</h2>
                            </div>

                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="module_users_login_service" class="col-sm-2 control-label">Sign In</label>
                                    <div class="col-sm-1">
                                        <div class="toggle-switch m-t-10">
                                            <input id="module_users_login_service" name="module_users_login_service" type="checkbox" hidden="hidden">
                                            <label for="module_users_login_service" class="ts-helper"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module_users_register_service" class="col-sm-2 control-label">Create Account</label>
                                    <div class="col-sm-1">
                                        <div class="toggle-switch m-t-10">
                                            <input id="module_users_register_service" name="module_users_register_service" type="checkbox" hidden="hidden">
                                            <label for="module_users_register_service" class="ts-helper"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module_users_reset_password_service" class="col-sm-2 control-label">Reset Password</label>
                                    <div class="col-sm-1">
                                        <div class="toggle-switch m-t-10">
                                            <input id="module_users_reset_password_service" name="module_users_reset_password_service" type="checkbox" hidden="hidden">
                                            <label for="module_users_reset_password_service" class="ts-helper"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="module_users_change_password_service" class="col-sm-2 control-label">Change Password</label>
                                    <div class="col-sm-1">
                                        <div class="toggle-switch m-t-10">
                                            <input id="module_users_change_password_service" name="module_users_change_password_service" type="checkbox" hidden="hidden">
                                            <label for="module_users_change_password_service" class="ts-helper"></label>
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
            $('#module_users_login_service').prop('checked', <?= (int) $data['module_users_login_service'] ?>);
            $('#module_users_register_service').prop('checked', <?= (int) $data['module_users_register_service'] ?>);
            $('#module_users_reset_password_service').prop('checked', <?= (int) $data['module_users_reset_password_service'] ?>);
            $('#module_users_change_password_service').prop('checked', <?= (int) $data['module_users_change_password_service'] ?>);

            $(document).ready(function() {
                $('#update-services').submit(function(event) {
                    event.preventDefault();

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/users/api/services/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Services settings has been updated',
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

                            $('#update-services').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
</html>