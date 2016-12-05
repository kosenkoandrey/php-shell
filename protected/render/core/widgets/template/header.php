<header id="header" class="media">
    <div class="pull-left h-logo">
        <a href="<?= APP::Module('Routing')->root ?>" class="hidden-xs">
            PHP-shell   
            <small>MICRO FRAMEWORK</small>
        </a>
    </div>

    <ul class="pull-right h-menu">
        <li class="dropdown hidden-xs">
            <a data-toggle="dropdown" href=""><i class="hm-icon zmdi zmdi-more-vert"></i></a>
            <ul class="dropdown-menu dm-icon pull-right">
                <li class="hidden-xs">
                    <a data-action="fullscreen" href=""><i class="zmdi zmdi-fullscreen"></i> Toggle Fullscreen</a>
                </li>
            </ul>
        </li>
        <li class="hm-alerts" data-user-alert="shell-app" data-ma-action="sidebar-open" data-ma-target="user-alerts">
            <a href=""><i class="hm-icon zmdi zmdi-settings"></i></a>
        </li>
        <li class="dropdown hm-profile">
            <?
            if (isset(APP::$modules['Users'])) {
                switch (APP::Module('Users')->user['role']) {
                    case 'default':
                        ?>
                        <a data-toggle="dropdown" href="">
                            <img src="<?= APP::Module('Routing')->root . 'public/ui/img/profile-pics/default.png' ?>">
                        </a>

                        <ul class="dropdown-menu pull-right dm-icon">
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/login"><i class="zmdi zmdi-key"></i> Sign In</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/register"><i class="zmdi zmdi-account-add"></i> Create an account</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/forgot-password"><i class="zmdi zmdi-mood-bad"></i> Forgot password?</a>
                            </li>
                        </ul>
                        <?
                        break;
                    default:
                        ?>
                        <a data-toggle="dropdown" href="">
                            <img src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5(APP::Module('Users')->user['email']) ?>?s=40&d=<?= urlencode(APP::Module('Routing')->root . 'public/ui/img/profile-pics/default.png') ?>&t=<?= time() ?>">
                        </a>

                        <ul class="dropdown-menu pull-right dm-icon">
                            <?
                            if (APP::Module('Users')->user['role'] == 'admin') {
                                ?>
                                <li>
                                    <a href="<?= APP::Module('Routing')->root ?>admin"><i class="zmdi zmdi-settings"></i> Admin Panel</a>
                                </li>   
                                <li class="divider"></li>
                                <?
                            }
                            ?>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/profile"><i class="zmdi zmdi-account"></i> View Profile</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/change-password"><i class="zmdi zmdi-key"></i> Change Password</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/logout"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                            </li>
                        </ul>
                        <?
                        break;
                }
            }
            ?>
        </li>
    </ul>

    <div class="media-body h-nav m-t-5">
        <div class="btn-group btn-group-lg">
            <? foreach ($data as $key => $value) { ?><a href="<?= APP::Module('Routing')->root . $value ?>" class="btn btn-default"><?= $key ?></a><? } ?>
        </div>
    </div>
</header>