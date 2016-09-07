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
                        <form id="update-user" class="form-horizontal" role="form">
                            <input type="hidden" name="id" value="<?= APP::Module('Routing')->get['user_id_hash'] ?>">
                            
                            <div class="card-header">
                                <h2>Edit user</h2>
                            </div>
                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">E-mail</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="email" class="form-control" id="email" value="<?= $data['user']['email'] ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <?
                                if (APP::Module('Sessions')->session['modules']['users']['double_auth']) {
                                    ?>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-2 control-label">Password</label>
                                        <div class="col-sm-3">
                                            <div class="fg-line">
                                                <input type="password" class="form-control" name="password" id="password" value="<?= APP::Module('Crypt')->Decode($data['user']['password']) ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="re-password" class="col-sm-2 control-label">Retype password</label>
                                        <div class="col-sm-3">
                                            <div class="fg-line">
                                                <input type="password" class="form-control" name="re-password" id="re-password" value="<?= APP::Module('Crypt')->Decode($data['user']['password']) ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                } else {
                                    ?>
                                    <div class="form-group">
                                        <label for="re-password" class="col-sm-2 control-label">Change password</label>
                                        <div class="col-sm-3">
                                            <div class="fg-line">
                                                <a class="btn palette-Orange bg waves-effect" href="<?= APP::Module('Routing')->root ?>users/login/double/<?= APP::Module('Crypt')->SafeB64Encode(APP::Module('Routing')->SelfUrl()) ?>"><i class="zmdi zmdi-lock-outline"></i> Disabled</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?>

                                <div class="form-group">
                                    <label for="role" class="col-sm-2 control-label">Role</label>
                                    <div class="col-sm-3">
                                        <select id="role" name="role" class="selectpicker">
                                            <? foreach ($data['roles'] as $role) { ?><option value="<?= $role ?>"><?= $role ?></option><? } ?>
                                        </select>
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
                $('#role').val('<?= $data['user']['role'] ?>');

                $('#update-user').submit(function(event) {
                    event.preventDefault();

                    
                    var role = $(this).find('#role');
                    role.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    if (role.val() === '') { role.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                    <?
                    if (APP::Module('Sessions')->session['modules']['users']['double_auth']) {
                        ?>
                        var password = $(this).find('#password');
                        var re_password = $(this).find('#re-password');

                        password.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                        re_password.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                        if (password.val() === '') { password.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                        if (password.val() !== re_password.val()) { re_password.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Passwords do not match</small>'); return false; }
                        <?
                    }
                    ?>
                                
                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/users/api/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: "Done!",
                                        text: 'User "<?= $data['user']['email'] ?>" has been updated',
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonText: "Ok",
                                        closeOnConfirm: false
                                    }, function(){
                                        window.location.href = '<?= APP::Module('Routing')->root ?>admin/users';
                                    });
                                    break;
                                case 'error': 
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {}
                                    });
                                    break;
                            }

                            $('#update-user').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                });
            });
        </script>
    </body>
</html>