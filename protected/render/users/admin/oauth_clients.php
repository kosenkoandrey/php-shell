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
                        <form id="update-social-networks" class="form-horizontal" role="form">
                            <div class="card-header">
                                <h2>OAuth clients</h2>
                            </div>
                            <div class="card-body card-padding">
                                <ul class="tab-nav m-b-15" role="tablist" data-tab-color="teal">
                                    <li class="active"><a href="#facebook" role="tab" data-toggle="tab">Facebook</a></li>
                                    <li role="presentation"><a href="#vk" role="tab" data-toggle="tab">VK</a></li>
                                    <li role="presentation"><a href="#google" role="tab" data-toggle="tab">Google</a></li>
                                    <li role="presentation"><a href="#yandex" role="tab" data-toggle="tab">Yandex</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active animated fadeIn in" id="facebook">
                                        <div class="form-group">
                                            <label for="module_users_oauth_client_fb_id" class="col-sm-1 control-label">ID</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="module_users_oauth_client_fb_id" 
                                                        id="module_users_oauth_client_fb_id" 
                                                        value="<?= isset(APP::Module('Users')->settings['module_users_oauth_client_fb_id']) ? APP::Module('Users')->settings['module_users_oauth_client_fb_id'] : '' ?>"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_users_oauth_client_fb_key" class="col-sm-1 control-label">Key</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="module_users_oauth_client_fb_key" 
                                                        id="module_users_oauth_client_fb_key" 
                                                        value="<?= isset(APP::Module('Users')->settings['module_users_oauth_client_fb_key']) ? APP::Module('Users')->settings['module_users_oauth_client_fb_key'] : '' ?>"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="vk">
                                        <div class="form-group">
                                            <label for="module_users_oauth_client_vk_id" class="col-sm-1 control-label">ID</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="module_users_oauth_client_vk_id" 
                                                        id="module_users_oauth_client_vk_id" 
                                                        value="<?= isset(APP::Module('Users')->settings['module_users_oauth_client_vk_id']) ? APP::Module('Users')->settings['module_users_oauth_client_vk_id'] : '' ?>"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_users_oauth_client_vk_key" class="col-sm-1 control-label">Key</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="module_users_oauth_client_vk_key" 
                                                        id="module_users_oauth_client_vk_key" 
                                                        value="<?= isset(APP::Module('Users')->settings['module_users_oauth_client_vk_key']) ? APP::Module('Users')->settings['module_users_oauth_client_vk_key'] : '' ?>"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="google">
                                        <div class="form-group">
                                            <label for="module_users_oauth_client_google_id" class="col-sm-1 control-label">ID</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="module_users_oauth_client_google_id" 
                                                        id="module_users_oauth_client_google_id" 
                                                        value="<?= isset(APP::Module('Users')->settings['module_users_oauth_client_google_id']) ? APP::Module('Users')->settings['module_users_oauth_client_google_id'] : '' ?>"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_users_oauth_client_google_key" class="col-sm-1 control-label">Key</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="module_users_oauth_client_google_key" 
                                                        id="module_users_oauth_client_google_key" 
                                                        value="<?= isset(APP::Module('Users')->settings['module_users_oauth_client_google_key']) ? APP::Module('Users')->settings['module_users_oauth_client_google_key'] : '' ?>"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="yandex">
                                        <div class="form-group">
                                            <label for="module_users_oauth_client_ya_id" class="col-sm-1 control-label">ID</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="module_users_oauth_client_ya_id" 
                                                        id="module_users_oauth_client_ya_id" 
                                                        value="<?= isset(APP::Module('Users')->settings['module_users_oauth_client_ya_id']) ? APP::Module('Users')->settings['module_users_oauth_client_ya_id'] : '' ?>"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_users_oauth_client_ya_key" class="col-sm-1 control-label">Key</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        name="module_users_oauth_client_ya_key" 
                                                        id="module_users_oauth_client_ya_key" 
                                                        value="<?= isset(APP::Module('Users')->settings['module_users_oauth_client_ya_key']) ? APP::Module('Users')->settings['module_users_oauth_client_ya_key'] : '' ?>"
                                                    >
                                                </div>
                                            </div>
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
                $('#update-social-networks').submit(function(event) {
                    event.preventDefault();

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/users/api/oauth/clients/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'OAuth clients settings has been updated',
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

                            $('#update-social-networks').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
</html>