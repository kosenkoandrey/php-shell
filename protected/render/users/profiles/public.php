<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - <?= isset($data['about']['username']) ? $data['about']['username'] : 'user' . $data['user']['id'] ?></title>

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
            isset($data['about']['username']) ? $data['about']['username'] : 'user' . $data['user']['id'] => 'users/profile/' . APP::Module('Routing')->get['user_id_hash']
        ]);
        ?>
        <section id="main" class="center">
            <section id="content">
                <div class="container">
                    <div class="card" id="profile-main">
                        <div class="pm-overview c-overflow">
                            <div class="pmo-pic">
                                <div class="p-relative">
                                    <img class="img-responsive" src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5($data['user']['email']) ?>?s=180&d=<?= urlencode(APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture']) ?>&t=<?= time() ?>">
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
                                    <li><i class="zmdi zmdi-pin-account"></i> <?= $data['user']['last_visit'] ?></li>
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
                            </ul>
                            
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="tab-about">
                                    <div class="pmb-block">
                                        <div class="pmbb-header">
                                            <h2><i class="zmdi zmdi-account m-r-5"></i> Basic</h2>
                                        </div>
                                        <div class="p-l-25">
                                            <div id="view-basic" class="pmbb-view">
                                                <dl class="dl-horizontal">
                                                    <dt>Username</dt>
                                                    <dd id="about-username-value"><?= isset($data['about']['username']) ? $data['about']['username'] : 'user' . $data['user']['id'] ?></dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="pmb-block">
                                        <div class="pmbb-header">
                                            <h2><i class="zmdi zmdi-phone m-r-5"></i> Contact</h2>
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
                                        </div>
                                    </div>
                                </div>
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
    </body>
  </html>