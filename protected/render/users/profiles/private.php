<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - <?= $data['user']['email'] ?></title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
        <? APP::Render('core/widgets/template/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('core/widgets/template/header', 'include', [
            $data['user']['email'] => 'users/profile'
        ]);
        ?>
        <section id="main" class="center">
            <section id="content">
                <div class="container">
                    <div class="card" id="profile-main">
                        <div class="pm-overview c-overflow">
                            <div class="pmo-pic">
                                <div class="p-relative">
                                    <img class="img-responsive " src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5($data['user']['email']) ?>?s=180&d=<?= urlencode(APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture']) ?>&t=<?= time() ?>">
                                </div>
                            </div>
                            
                            <?
                            if (isset(APP::$modules['Likes'])) {
                                ?>
                                <div class="m-t-15 m-r-15 m-b-0 m-l-15">
                                    <?
                                    APP::Render('likes/widgets/default', 'include', [
                                        'type' => APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', "User", PDO::PARAM_STR]]),
                                        'id' => $data['user']['id'],
                                        'text' => 'Like',
                                        'class' => ['btn-lg', 'btn-block', 'f-700'],
                                        'details' => true
                                    ]);
                                    ?>
                                </div>
                                <?
                            }
                            ?>
                            
                            <div class="pmo-block pmo-contact hidden-xs">
                                <ul class="m-b-25">
                                    <li><i class="zmdi zmdi-account-add"></i> <?= $data['user']['reg_date'] ?></li>
                                </ul>
                                
                                <?
                                if (count($data['social-profiles'])) {
                                    ?>
                                    <h2>Accounts</h2>
                                    <ul>
                                        <?
                                        foreach ($data['social-profiles'] as $profile) {
                                            switch ($profile['service']) {
                                                case 'vk': ?><li><i class="zmdi zmdi-vk"></i> <a href="https://vk.com/id<?= $profile['extra'] ?>" target="_blank" class="c-teal"><?= $profile['extra'] ?></a></li><? break;
                                                case 'fb': ?><li><i class="zmdi zmdi-facebook-box"></i> <a href="http://facebook.com/profile.php?id=<?= $profile['extra'] ?>" target="_blank"><?= $profile['extra'] ?></a></li><? break;
                                                case 'google': ?><li><i class="zmdi zmdi-google-plus-box"></i> <a href="https://plus.google.com/u/0/<?= $profile['extra'] ?>" target="_blank"><?= $profile['extra'] ?></a></li><? break;
                                                case 'ya': ?><li><i class="zmdi zmdi-yandex"></i> <?= $profile['extra'] ?></li><? break;
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <?
                                }
                                ?>
                            </div>
                        </div>

                        <div class="pm-body clearfix">
                            <ul class="tab-nav" data-tab-color="teal">
                                <li class="active waves-effect"><a href="#tab-about" aria-controls="tab-about" role="tab" data-toggle="tab">About</a></li>
                                <? if ($data['comments']) { ?><li class="waves-effect"><a href="#tab-comments" aria-controls="tab-comments" role="tab" data-toggle="tab">Comments</a></li><? } ?>
                                <? if ($data['likes']) { ?><li class="waves-effect"><a href="#tab-likes" aria-controls="tab-likes" role="tab" data-toggle="tab">Likes</a></li><? } ?>
                            </ul>
                            
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="tab-about">
                                    <div class="pmb-block">
                                        <div class="pmbb-header">
                                            <h2><i class="zmdi zmdi-account m-r-5"></i> Basic</h2>

                                            <ul class="actions">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0)" data-toggle="dropdown"><i class="zmdi zmdi-more-vert"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a class="toggle-basic" href="javascript:void(0)">Edit</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="p-l-25">
                                            <div id="view-basic" class="pmbb-view">
                                                <dl class="dl-horizontal">
                                                    <dt>Username</dt>
                                                    <dd id="about-username-value"><?= isset($data['about']['username']) ? $data['about']['username'] : 'user' . $data['user']['id'] ?></dd>
                                                </dl>
                                            </div>

                                            <form id="form-basic" class="pmbb-edit">
                                                <dl class="dl-horizontal">
                                                    <dt class="p-t-10">Username</dt>
                                                    <dd>
                                                        <div class="fg-line">
                                                            <input type="text" id="about_username" name="about[username]" class="form-control" placeholder="user<?= $data['user']['id'] ?>">
                                                        </div>
                                                    </dd>
                                                </dl>
                                                <div class="m-t-30">
                                                    <button type="submit" class="btn palette-Teal bg waves-effect">Save</button>
                                                    <button type="button" class="toggle-basic btn btn-link c-black">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="pmb-block">
                                        <div class="pmbb-header">
                                            <h2><i class="zmdi zmdi-phone m-r-5"></i> Contact</h2>

                                            <ul class="actions">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0)" data-toggle="dropdown"><i class="zmdi zmdi-more-vert"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a class="toggle-contact" href="javascript:void(0)">Edit</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="p-l-25">
                                            <div id="view-contact" class="pmbb-view">
                                                <dl class="dl-horizontal">
                                                    <dt>Mobile Phone</dt>
                                                    <dd id="about-mobile-phone-value"><?= isset($data['about']['mobile_phone']) ? $data['about']['mobile_phone'] : 'none' ?></dd>
                                                </dl>
                                                <dl class="dl-horizontal">
                                                    <dt>Twitter</dt>
                                                    <dd id="about-twitter-value"><?= isset($data['about']['twitter']) ? $data['about']['twitter'] : 'none' ?></dd>
                                                </dl>
                                                <dl class="dl-horizontal">
                                                    <dt>Skype</dt>
                                                    <dd id="about-skype-value"><?= isset($data['about']['skype']) ? $data['about']['skype'] : 'none' ?></dd>
                                                </dl>
                                            </div>

                                            <form id="form-contact" class="pmbb-edit">
                                                <dl class="dl-horizontal">
                                                    <dt class="p-t-10">Mobile Phone</dt>
                                                    <dd>
                                                        <div class="fg-line">
                                                            <input type="text" id="about_mobile_phone" name="about[mobile_phone]" class="form-control input-mask" data-mask="+000000000000" maxlength="15" autocomplete="off">
                                                        </div>
                                                    </dd>
                                                </dl>
                                                <dl class="dl-horizontal">
                                                    <dt class="p-t-10">Twitter</dt>
                                                    <dd>
                                                        <div class="fg-line">
                                                            <input type="text" id="about_twitter" name="about[twitter]" class="form-control">
                                                        </div>
                                                    </dd>
                                                </dl>
                                                <dl class="dl-horizontal">
                                                    <dt class="p-t-10">Skype</dt>
                                                    <dd>
                                                        <div class="fg-line">
                                                            <input type="text" id="about_skype" name="about[skype]" class="form-control">
                                                        </div>
                                                    </dd>
                                                </dl>
                                                <div class="m-t-30">
                                                    <button type="submit" class="btn palette-Teal bg waves-effect">Save</button>
                                                    <button type="button" class="toggle-contact btn btn-link c-black">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <?
                                if ($data['comments']) {
                                    ?>
                                    <div role="tabpanel" class="tab-pane" id="tab-comments">
                                        <?
                                        foreach ($data['comments'] as $comment) {
                                            ?>
                                            <div class="media p-t-10 p-l-25 p-r-25">
                                                <div class="pull-left">
                                                    <a href="<?= $comment['url'] ?>#comment-<?= APP::Module('Crypt')->Encode($comment['id']) ?>" target="_blank" class="btn btn-default btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-comment-text"></i></a>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">
                                                        <p class="m-b-5 f-12 c-gray"><i class="zmdi zmdi-calendar"></i> <?= date('Y-m-d H:i:s', $comment['up_date']) ?></p>
                                                    </h4>
                                                    <p style="white-space: pre-wrap" class="m-b-10"><?= $comment['message'] ?></p>
                                                    <p><a href="<?= $comment['url'] ?>#comment-<?= APP::Module('Crypt')->Encode($comment['id']) ?>" target="_blank" class="btn palette-Teal bg waves-effect btn-xs"><i class="zmdi zmdi-open-in-new"></i> View</a></p>
                                                </div>
                                            </div>
                                            <?
                                        }
                                        ?>
                                    </div>
                                    <?
                                }
                                
                                if ($data['likes']) {
                                    ?>
                                    <div role="tabpanel" class="tab-pane" id="tab-likes">
                                        <?
                                        foreach ($data['likes'] as $like) {
                                            ?>
                                            <div class="media p-t-10 p-l-25 p-r-25">
                                                <div class="pull-left">
                                                    <a href="<?= $like['url'] ?>" target="_blank" class="btn btn-default btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-favorite"></i></a>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">
                                                        <p class="m-b-5 f-12 c-gray"><i class="zmdi zmdi-calendar"></i> <?= date('Y-m-d H:i:s', $like['up_date']) ?></p>
                                                    </h4>
                                                    <p><a href="<?= $like['url'] ?>" target="_blank" class="btn palette-Teal bg waves-effect btn-xs"><i class="zmdi zmdi-open-in-new"></i> View</a></p>
                                                </div>
                                            </div>
                                            <?
                                        }
                                        ?>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <? APP::Render('core/widgets/template/footer') ?>
        </section>

        <? APP::Render('core/widgets/page_loader') ?>
        <? APP::Render('core/widgets/ie_warning') ?>

        <!-- Javascript Libraries -->
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/moment/min/moment.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/input-mask/input-mask.min.js"></script>

        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                $('#about_username').val('<?= isset($data['about']['username']) ? $data['about']['username'] : '' ?>');
                $('#about_mobile_phone').val('<?= isset($data['about']['mobile_phone']) ? $data['about']['mobile_phone'] : '' ?>');
                $('#about_twitter').val('<?= isset($data['about']['twitter']) ? $data['about']['twitter'] : '' ?>');
                $('#about_skype').val('<?= isset($data['about']['skype']) ? $data['about']['skype'] : '' ?>');

                $('body').on('click', '.toggle-basic', function() {
                    $('#view-basic').toggle();
                    $('#form-basic').toggle();
                });
                
                $('body').on('click', '.toggle-contact', function() {
                    $('#view-contact').toggle();
                    $('#form-contact').toggle();
                });

                $('#form-basic').submit(function(event) {
                    event.preventDefault();

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);
                    $(this).find('.toggle-basic').hide();

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>users/api/about/update.json',
                        data: $(this).serialize(),
                        success: function() {
                            swal({
                                title: 'Done!',
                                text: 'Basic information has been updated',
                                type: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            $('#form-basic').find('[type="submit"]').html('Save').attr('disabled', false);
                            $('#form-basic').find('.toggle-basic').show();
                            
                            var about_username = $('#about_username').val();
                            
                            $('#about-username-value').html(about_username ? about_username : 'user<?= $data['user']['id'] ?>');
                            
                            $('#view-basic').toggle();
                            $('#form-basic').toggle();
                        }
                    });
                });
                
                $('#form-contact').submit(function(event) {
                    event.preventDefault();

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);
                    $(this).find('.toggle-contact').hide();
                    
                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>users/api/about/update.json',
                        data: $(this).serialize(),
                        success: function() {
                            swal({
                                title: 'Done!',
                                text: 'Contact information has been updated',
                                type: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            $('#form-contact').find('[type="submit"]').html('Save').attr('disabled', false);
                            $('#form-contact').find('.toggle-contact').show();
                            
                            var about_mobile_phone = $('#about_mobile_phone').val();
                            var about_twitter = $('#about_twitter').val();
                            var about_skype = $('#about_skype').val();
                            
                            $('#about-mobile-phone-value').html(about_mobile_phone ? about_mobile_phone : 'none');
                            $('#about-twitter-value').html(about_twitter ? about_twitter : 'none');
                            $('#about-skype-value').html(about_skype ? about_skype : 'none');
                            
                            $('#view-contact').toggle();
                            $('#form-contact').toggle();
                        }
                    });
                });
            });
        </script>
    </body>
  </html>