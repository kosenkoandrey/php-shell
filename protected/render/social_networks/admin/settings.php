<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Social Networks</title>

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
            'Social Networks' => 'admin/social_networks/settings'
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
                                    <li class="active"><a href="#settings-vk" role="tab" data-toggle="tab">VK</a></li>
                                    <li role="presentation"><a href="#settings-fb" role="tab" data-toggle="tab">Facebook</a></li>
                                    <li role="presentation"><a href="#settings-gplus" role="tab" data-toggle="tab">Google+</a></li>
                                    <li role="presentation"><a href="#settings-twitter" role="tab" data-toggle="tab">Twitter</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active animated fadeIn in" id="settings-vk">
                                        <div class="form-group">
                                            <label for="module_social_networks_vk_gid" class="col-sm-2 control-label">GID</label>
                                            <div class="col-sm-4">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_social_networks_vk_gid" id="module_social_networks_vk_gid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="settings-fb">
                                        <div class="form-group">
                                            <label for="module_social_networks_fb_name" class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-4">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_social_networks_fb_name" id="module_social_networks_fb_name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="settings-gplus">
                                        <div class="form-group">
                                            <label for="module_social_networks_gplus_user" class="col-sm-2 control-label">User</label>
                                            <div class="col-sm-4">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_social_networks_gplus_user" id="module_social_networks_gplus_user">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="module_social_networks_gplus_key" class="col-sm-2 control-label">Key</label>
                                            <div class="col-sm-6">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_social_networks_gplus_key" id="module_social_networks_gplus_key">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div role="tabpanel" class="tab-pane animated fadeIn" id="settings-twitter">
                                        <div class="form-group">
                                            <label for="module_social_networks_twitter_user" class="col-sm-2 control-label">User</label>
                                            <div class="col-sm-4">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="module_social_networks_twitter_user" id="module_social_networks_twitter_user">
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
                $('#module_social_networks_vk_gid').val('<?= APP::Module('SocialNetworks')->settings['module_social_networks_vk_gid'] ?>');
                $('#module_social_networks_fb_name').val('<?= APP::Module('SocialNetworks')->settings['module_social_networks_fb_name'] ?>');
                $('#module_social_networks_gplus_user').val('<?= APP::Module('SocialNetworks')->settings['module_social_networks_gplus_user'] ?>');
                $('#module_social_networks_gplus_key').val('<?= APP::Module('SocialNetworks')->settings['module_social_networks_gplus_key'] ?>');
                $('#module_social_networks_twitter_user').val('<?= APP::Module('SocialNetworks')->settings['module_social_networks_twitter_user'] ?>');

                $('#update-settings').submit(function(event) {
                    event.preventDefault();

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/social_networks/api/settings/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Social networks settings has been updated',
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