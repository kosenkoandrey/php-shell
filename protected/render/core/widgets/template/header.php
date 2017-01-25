<!--
<header id="header" class="media">
    <div class="pull-left h-logo">
        <a href="<?= APP::Module('Routing')->root ?>users/profile" class="hidden-xs">
            <i class="zmdi zmdi-account-box"></i> мой
            <small>GLAMURNENKO.RU</small>
        </a>
    </div>

    <ul class="pull-right h-menu">
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
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/login"><i class="zmdi zmdi-key"></i> Войти</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/register"><i class="zmdi zmdi-account-add"></i> Зарегистрироваться</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/forgot-password"><i class="zmdi zmdi-mood-bad"></i> Забыли пароль?</a>
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
                                    <a href="<?= APP::Module('Routing')->root ?>admin"><i class="zmdi zmdi-settings"></i> Панель управления</a>
                                </li>   
                                <?
                            }
                            ?>
                            <li class="hidden-xs">
                                <a data-action="fullscreen" href=""><i class="zmdi zmdi-fullscreen"></i> Полноэкранный режим</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/profile"><i class="zmdi zmdi-account"></i> Мой профиль</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/change-password"><i class="zmdi zmdi-key"></i> Изменить пароль</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/logout"><i class="zmdi zmdi-time-restore"></i> Выйти</a>
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
-->

<div class="card" style="margin-bottom: 1px; margin-top: 30px; padding: 40px 30px;">
    <a href="http://glamurnenko.ru" target="_blank" style="border: 0"><img src="http://pult.glamurnenko.ru/public/WebApp/images/logo.png" style="width: 500px;"></a>

    <ul class="pull-right h-menu">
        <li class="dropdown hm-profile">
            <?
            if (isset(APP::$modules['Users'])) {
                switch (APP::Module('Users')->user['role']) {
                    case 'default':
                        ?>
                        <a data-toggle="dropdown" href="" class="media-object avatar-img z-depth-1-bottom">
                            <img src="<?= APP::Module('Routing')->root . 'public/ui/img/profile-pics/default.png' ?>" class="media-object avatar-img z-depth-1-bottom">
                        </a>

                        <ul class="dropdown-menu pull-right dm-icon">
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/login"><i class="zmdi zmdi-key"></i> Войти</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/register"><i class="zmdi zmdi-account-add"></i> Зарегистрироваться</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/forgot-password"><i class="zmdi zmdi-mood-bad"></i> Забыли пароль?</a>
                            </li>
                        </ul>
                        <?
                        break;
                    default:
                        ?>
                        <a data-toggle="dropdown" href="">
                            <img src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5(APP::Module('Users')->user['email']) ?>?s=40&d=<?= urlencode(APP::Module('Routing')->root . 'public/ui/img/profile-pics/default.png') ?>&t=<?= time() ?>" class="media-object avatar-img z-depth-1-bottom">
                        </a>

                        <ul class="dropdown-menu pull-right dm-icon">
                            <?
                            if (APP::Module('Users')->user['role'] == 'admin') {
                                ?>
                                <li>
                                    <a href="<?= APP::Module('Routing')->root ?>admin"><i class="zmdi zmdi-settings"></i> Панель управления</a>
                                </li>   
                                <?
                            }
                            ?>
                            <li class="hidden-xs">
                                <a data-action="fullscreen" href=""><i class="zmdi zmdi-fullscreen"></i> Полноэкранный режим</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/profile"><i class="zmdi zmdi-account"></i> Мой профиль</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/actions/change-password"><i class="zmdi zmdi-key"></i> Изменить пароль</a>
                            </li>
                            <li>
                                <a href="<?= APP::Module('Routing')->root ?>users/logout"><i class="zmdi zmdi-time-restore"></i> Выйти</a>
                            </li>
                        </ul>
                        <?
                        break;
                }
            }
            ?>
        </li>
    </ul>
</div>