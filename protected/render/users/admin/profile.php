<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Личная карточка - <?= $data['user']['email'] ?></title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Личная карточка' => 'admin/users'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="c-header">
                        <h2><?= $data['user']['email'] ?>
                            <small>
                                <?
                                switch ($data['user']['role']) {
                                    case 'admin': echo 'АДМИНИСТРАТОР'; break;
                                    case 'new': echo 'НЕАКТИВИРОВАННЫЙ ПОЛЬЗОВАТЕЛЬ'; break;
                                    case 'user': echo 'ПОЛЬЗОВАТЕЛЬ'; break;
                                    default: echo $data['user']['role']; break;
                                }
                                ?>
                            </small>
                        </h2>
                    </div>

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
                                        'text' => 'Мне нравится',
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
                                    <li><i class="zmdi zmdi-format-list-numbered"></i> <?= $data['user']['id'] ?></li>
                                    <li><i class="zmdi zmdi-account-add"></i> <?= $data['user']['reg_date'] ?></li>
                                    <li><i class="zmdi zmdi-pin-account"></i> <?= $data['user']['last_visit'] ?></li>
                                </ul>
                                
                                <?
                                if (count($data['social-profiles'])) {
                                    ?>
                                    <h2>Социальные сети</h2>
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
                                <li class="active waves-effect"><a href="#tab-about" aria-controls="tab-about" role="tab" data-toggle="tab">ОСНОВНОЕ</a></li>
                                <? if ($data['mail']) { ?><li class="waves-effect"><a href="#tab-mail" aria-controls="tab-mail" role="tab" data-toggle="tab">ПИСЬМА</a></li><? } ?>
                                <? if ($data['tunnels']) { ?><li class="waves-effect"><a href="#tab-tunnels" aria-controls="tab-tunnels" role="tab" data-toggle="tab">ТУННЕЛИ</a></li><? } ?>
                                <? if ($data['tags']) { ?><li class="waves-effect"><a href="#tab-tags" aria-controls="tab-tags" role="tab" data-toggle="tab">ТЕГИ</a></li><? } ?>
                                <? if ($data['comments']) { ?><li class="waves-effect"><a href="#tab-comments" aria-controls="tab-comments" role="tab" data-toggle="tab">КОММЕНТАРИИ</a></li><? } ?>
                                <? if ($data['likes']) { ?><li class="waves-effect"><a href="#tab-likes" aria-controls="tab-likes" role="tab" data-toggle="tab">ОЦЕНКИ</a></li><? } ?>
                                <? if ($data['premium']) { ?><li class="waves-effect"><a href="#tab-premium" aria-controls="tab-premium" role="tab" data-toggle="tab">ПЛАТНЫЕ МАТЕРИАЛЫ</a></li><? } ?>
                            </ul>
                            
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="tab-about">
                                    <div class="pmb-block">
                                        <div class="pmbb-header">
                                            <h2><i class="zmdi zmdi-account m-r-5"></i> Основное</h2>

                                            <ul class="actions">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0)" data-toggle="dropdown"><i class="zmdi zmdi-more-vert"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a class="toggle-basic" href="javascript:void(0)">Редарктировать</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="p-l-25">
                                            <div id="view-basic" class="pmbb-view">
                                                <dl class="dl-horizontal">
                                                    <dt>Имя пользователя</dt>
                                                    <dd id="about-username-value"><?= isset($data['about']['username']) ? $data['about']['username'] : 'user' . $data['user']['id'] ?></dd>
                                                </dl>
                                                <dl class="dl-horizontal">
                                                    <dt>Состояние</dt>
                                                    <dd id="about-state-value">
                                                        <?
                                                        if (isset($data['about']['state'])) {
                                                            switch ($data['about']['state']) {
                                                                case 'unknown': echo 'не изветстно'; break;
                                                                case 'active': echo 'активный'; break;
                                                                case 'inactive': echo 'неактивный'; break;
                                                                case 'blacklist': echo 'в черном списке'; break;
                                                                case 'dropped': echo 'дропнутый'; break;
                                                                
                                                            }
                                                        } else {
                                                            echo 'не изветстно';
                                                        }
                                                        ?>
                                                    </dd>
                                                </dl>
                                            </div>

                                            <form id="form-basic" class="pmbb-edit">
                                                <input type="hidden" name="user" value="<?= APP::Module('Crypt')->Encode($data['user']['id']) ?>">
                                                
                                                <dl class="dl-horizontal">
                                                    <dt class="p-t-10">Имя пользователя</dt>
                                                    <dd>
                                                        <div class="fg-line">
                                                            <input type="text" id="about_username" name="about[username]" class="form-control" placeholder="user<?= $data['user']['id'] ?>">
                                                        </div>
                                                    </dd>
                                                </dl>
                                                <dl class="dl-horizontal">
                                                    <dt class="p-t-10">Состояние</dt>
                                                    <dd>
                                                        <div class="fg-line" style="width: 50%;">
                                                            <select id="about_state" name="about[state]" class="selectpicker">
                                                                <option value="unknown">не изветстно</option>
                                                                <option value="active">активный</option>
                                                                <option value="inactive">неактивный</option>
                                                                <option value="blacklist">в черном списке</option>
                                                                <option value="dropped">дропнутый</option>
                                                            </select>
                                                        </div>
                                                    </dd>
                                                </dl>
                                                <div class="m-t-30">
                                                    <button type="submit" class="btn palette-Teal bg waves-effect">Сохранить</button>
                                                    <button type="button" class="toggle-basic btn btn-link c-black">Отмена</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="pmb-block">
                                        <div class="pmbb-header">
                                            <h2><i class="zmdi zmdi-phone m-r-5"></i> Контакты</h2>

                                            <ul class="actions">
                                                <li class="dropdown">
                                                    <a href="javascript:void(0)" data-toggle="dropdown"><i class="zmdi zmdi-more-vert"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a class="toggle-contact" href="javascript:void(0)">Редактировать</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="p-l-25">
                                            <div id="view-contact" class="pmbb-view">
                                                <dl class="dl-horizontal">
                                                    <dt>Телефон</dt>
                                                    <dd id="about-mobile-phone-value"><?= isset($data['about']['mobile_phone']) ? $data['about']['mobile_phone'] : 'нет' ?></dd>
                                                </dl>
                                                <dl class="dl-horizontal">
                                                    <dt>Twitter</dt>
                                                    <dd id="about-twitter-value"><?= isset($data['about']['twitter']) ? $data['about']['twitter'] : 'нет' ?></dd>
                                                </dl>
                                                <dl class="dl-horizontal">
                                                    <dt>Skype</dt>
                                                    <dd id="about-skype-value"><?= isset($data['about']['skype']) ? $data['about']['skype'] : 'нет' ?></dd>
                                                </dl>
                                            </div>

                                            <form id="form-contact" class="pmbb-edit">
                                                <input type="hidden" name="user" value="<?= APP::Module('Crypt')->Encode($data['user']['id']) ?>">
                                                
                                                <dl class="dl-horizontal">
                                                    <dt class="p-t-10">Телефон</dt>
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
                                                    <button type="submit" class="btn palette-Teal bg waves-effect">Сохранить</button>
                                                    <button type="button" class="toggle-contact btn btn-link c-black">Отмена</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <?
                                    if (isset(APP::$modules['Comments'])) {
                                        ?>
                                        <div class="p-t-25">
                                            <?
                                            $comment_object_type = APP::Module('DB')->Select(APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchColumn', 0], ['id'], 'comments_objects', [['name', '=', "UserAdmin", PDO::PARAM_STR]]);
                                            
                                            APP::Render('comments/widgets/default/list', 'include', [
                                                'type' => $comment_object_type,
                                                'id' => $data['user']['id'],
                                                'likes' => true,
                                                'class' => [
                                                    'holder' => 'palette-Grey-50 bg p-l-10'
                                                ]
                                            ]);

                                            APP::Render('comments/widgets/default/form', 'include', [
                                                'type' => $comment_object_type,
                                                'id' => $data['user']['id'],
                                                'login' => [],
                                                'class' => [
                                                    'holder' => false,
                                                    'list' => 'palette-Grey-50 bg p-l-10'
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                        <?
                                    }
                                    ?>
                                </div>
                                
                                <?
                                if ($data['mail']) {
                                    ?>
                                    <div role="tabpanel" class="tab-pane" id="tab-mail">
                                        <div class="pmb-block">
                                            <div class="pmbb-header">
                                                <h2><i class="zmdi zmdi-mail-send m-r-5"></i> Всего отправлено <?= count($data['mail']) ?> писем</h2>
                                            </div>
                                        </div>
                                        <table class="table table-hover table-vmiddle">
                                            <tbody>
                                                <?
                                                foreach ($data['mail'] as $item) {
                                                    $mail_icon = false;
                                                    
                                                    switch ($item['log']['state']) {
                                                        case 'wait': $mail_icon = ['Grey-400', 'time']; break;
                                                        case 'error': $mail_icon = ['Red-400', 'close']; break;
                                                        case 'success': $mail_icon = ['Orange-400', 'email']; break;
                                                    }
                                                    
                                                    $mail_tags = array_reverse($item['tags']);
                                                    ?>
                                                    <tr>
                                                        <td style="width: 60px;">
                                                            <span style="display: inline-block" class="avatar-char palette-<?= $mail_icon[0] ?> bg"><i class="zmdi zmdi-<?= $mail_icon[1] ?>"></i></span>
                                                        </td>
                                                        <td style="font-size: 16px;">
                                                            <a class="mail_events" data-id="<?= $item['log']['id'] ?>" style="color: #4C4C4C" href="javascript:void(0)"><?= $item['log']['letter_subject'] ?></a>
                                                            <div style="font-size: 11px;"><?= $item['log']['cr_date'] ?></div>
                                                            <div style="font-size: 12px; margin-top: 5px;"><?= count($mail_tags) ? implode(' <i class="zmdi zmdi-long-arrow-right"></i> ', $mail_tags) : 'Нет событий' ?></div>
                                                        </td>
                                                        <td>
                                                            <a target="_blank" href="<?= APP::Module('Routing')->root ?>mail/html/<?= APP::Module('Crypt')->Encode($item['log']['id']) ?>" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-code-setting"></span></a>
                                                            <a target="_blank" href="<?= APP::Module('Routing')->root ?>mail/plaintext/<?= APP::Module('Crypt')->Encode($item['log']['id']) ?>" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-text-format"></span></a>
                                                        </td>
                                                    </tr>
                                                    <?
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                
                                    <div class="modal fade" id="mail-events-modal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Подробности отправки письма</h4>
                                                </div>
                                                <div class="details">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>ID отправки</td>
                                                                <td class="mail_id"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Состояние</td>
                                                                <td class="mail_state"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Ответ</td>
                                                                <td class="mail_result"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Кол-во попыток</td>
                                                                <td class="mail_retries"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Время ответа</td>
                                                                <td class="mail_ping"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Дата отправки</td>
                                                                <td class="mail_cr_date"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Тема письма</td>
                                                                <td class="mail_letter_subject"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Приоритет</td>
                                                                <td class="mail_letter_priority"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Имя отправителя</td>
                                                                <td class="mail_sender_name"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>E-Mail отправителя</td>
                                                                <td class="mail_sender_email"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Модуль транспорта</td>
                                                                <td class="mail_transport_module"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Метод транспорта</td>
                                                                <td class="mail_transport_method"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-header">
                                                    <h4 class="modal-title">События связанные с письмом</h4>
                                                </div>
                                                <div class="modal-body events"></div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-link" data-dismiss="modal">Закрыть</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                
                                if ($data['tunnels']) {
                                    ?>
                                    <div role="tabpanel" class="tab-pane" id="tab-tunnels">
                                        <div class="pmb-block">
                                            <div class="pmbb-header">
                                                <h2><i class="zmdi zmdi-arrow-split m-r-5"></i> Всего подписок на <?= count($data['tunnels']) ?> туннелей</h2>
                                            </div>
                                        </div>
                                        <table class="table table-hover table-vmiddle">
                                            <tbody>
                                                <?
                                                foreach ($data['tunnels'] as $item) {
                                                    $tunnel_icon = false;
                                                    
                                                    switch ($item['info']['state']) {
                                                        case 'pause': $tunnel_icon = ['Grey-400', 'time']; break;
                                                        case 'complete': $tunnel_icon = ['Green-400', 'check']; break;
                                                        case 'active': $tunnel_icon = ['Orange-400', 'arrow-split']; break;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td style="width: 60px;">
                                                            <span style="display: inline-block" class="avatar-char palette-<?= $tunnel_icon[0] ?> bg"><i class="zmdi zmdi-<?= $tunnel_icon[1] ?>"></i></span>
                                                        </td>
                                                        <td style="font-size: 16px;">
                                                            <a class="tunnel_tags" data-id="<?= $item['info']['id'] ?>" style="color: #4C4C4C" href="javascript:void(0)"><?= $item['info']['tunnel_name'] ?></a>
                                                            <div style="font-size: 11px;"><?= count($item['tags']) ?> событий</div>
                                                        </td>
                                                        <!--
                                                        <td>
                                                            <a target="_blank" href="#" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-code-setting"></span></a>
                                                            <a target="_blank" href="#" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-text-format"></span></a>
                                                            <a target="_blank" href="#" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-text-format"></span></a>
                                                        </td>
                                                        -->
                                                    </tr>
                                                    <?
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                
                                    <div class="modal fade" id="tunnel-tags-modal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Подробности подписки на туннель</h4>
                                                </div>
                                                <div class="details">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>ID подписки</td>
                                                                <td class="tunnel_uid"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Состояние</td>
                                                                <td class="tunnel_state"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Тип туннеля</td>
                                                                <td class="tunnel_type"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>ID туннеля</td>
                                                                <td class="tunnel_id"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Наименование туннеля</td>
                                                                <td class="tunnel_name"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-header">
                                                    <h4 class="modal-title">События связанные с туннелем</h4>
                                                </div>
                                                <div class="modal-body tags"></div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-link" data-dismiss="modal">Закрыть</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                
                                if ($data['tags']) {
                                    ?>
                                    <div role="tabpanel" class="tab-pane" id="tab-tags">
                                        <div class="pmb-block">
                                            <div class="pmbb-header">
                                                <h2><i class="zmdi zmdi-labels m-r-5"></i> Всего <?= count($data['tags']) ?> тег</h2>
                                            </div>
                                        </div>
                                        <table class="table table-hover table-vmiddle">
                                            <tbody>
                                                <?
                                                foreach ($data['tags'] as $item) {
                                                    ?>
                                                    <tr>
                                                        <td style="width: 60px;">
                                                            <span style="display: inline-block" class="avatar-char palette-Orange-400 bg"><i class="zmdi zmdi-label"></i></span>
                                                        </td>
                                                        <td style="font-size: 16px;">
                                                            <a class="tags" data-id="<?= $item['id'] ?>" style="color: #4C4C4C" href="javascript:void(0)"><?= $item['item'] ?></a>
                                                            <div style="font-size: 11px;"><?= $item['cr_date'] ?></div>
                                                        </td>
                                                        <!--
                                                        <td>
                                                            <a target="_blank" href="#" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-code-setting"></span></a>
                                                            <a target="_blank" href="#" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-text-format"></span></a>
                                                            <a target="_blank" href="#" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-text-format"></span></a>
                                                        </td>
                                                        -->
                                                    </tr>
                                                    <?
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                
                                    <div class="modal fade" id="tags-modal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Детали тега</h4>
                                                </div>
                                                <div class="details">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>ID тега</td>
                                                                <td class="tag_id"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Наименование</td>
                                                                <td class="tag_item"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Значение</td>
                                                                <td class="tag_value">
                                                                    <pre></pre>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Дата создания</td>
                                                                <td class="tag_cr_date"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-link" data-dismiss="modal">Закрыть</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                                
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
                                                    <p><a href="<?= $comment['url'] ?>#comment-<?= APP::Module('Crypt')->Encode($comment['id']) ?>" target="_blank" class="btn palette-Teal bg waves-effect btn-xs"><i class="zmdi zmdi-open-in-new"></i> Перейти</a></p>
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
                                                    <p><a href="<?= $like['url'] ?>" target="_blank" class="btn palette-Teal bg waves-effect btn-xs"><i class="zmdi zmdi-open-in-new"></i> Перейти</a></p>
                                                </div>
                                            </div>
                                            <?
                                        }
                                        ?>
                                    </div>
                                    <?
                                }
                                
                                if ($data['premium']) {
                                    ?>
                                    <div role="tabpanel" class="tab-pane" id="tab-premium">
                                        <div class="pmb-block">
                                            <div class="pmbb-header">
                                                <h2><i class="zmdi zmdi-lock-open m-r-5"></i> У вас есть доступ к следующим материалам</h2>
                                            </div>
                                        </div>
                                        <table class="table table-hover table-vmiddle">
                                            <tbody>
                                                <?
                                                foreach ($data['premium'] as $item) {
                                                    switch ($item['type']) {
                                                        case 'g':
                                                            ?>
                                                            <tr>
                                                                <td style="font-size: 16px"><span style="display: inline-block" class="avatar-char palette-Teal bg m-r-5"><i class="zmdi zmdi-folder"></i></span> <a style="color: #4C4C4C" href="<?= APP::Module('Routing')->root ?>admin/members/pages/<?= APP::Module('Crypt')->Encode($item['id']) ?>" target="_blank"><?= $item['title'] ?></a></td>
                                                            </tr>
                                                            <?
                                                            break;
                                                        case 'p':
                                                            ?>
                                                            <tr>
                                                                <td style="font-size: 16px;"><span style="display: inline-block" class="avatar-char palette-Orange-400 bg m-r-5"><i class="zmdi zmdi-file"></i></span> <a style="color: #4C4C4C" href="<?= APP::Module('Routing')->root ?>admin/members/page/<?= APP::Module('Crypt')->Encode($item['id']) ?>" target="_blank"><?= $item['title'] ?></a></td>
                                                            </tr>
                                                            <?
                                                            break;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootstrap-growl/bootstrap-growl.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/moment/min/moment.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/input-mask/input-mask.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
        
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                $('#about_username').val('<?= isset($data['about']['username']) ? $data['about']['username'] : '' ?>');
                $('#about_state').val('<?= isset($data['about']['state']) ? $data['about']['state'] : 'unknown' ?>');
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

                    $(this).find('[type="submit"]').html('Подождите...').attr('disabled', true);
                    $(this).find('.toggle-basic').hide();

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/users/api/about/update.json',
                        data: $(this).serialize(),
                        success: function() {
                            swal({
                                title: 'Основная информация была обновлена',
                                type: 'success',
                                timer: 2500,
                                showConfirmButton: false
                            });

                            $('#form-basic').find('[type="submit"]').html('Save').attr('disabled', false);
                            $('#form-basic').find('.toggle-basic').show();
                            
                            var about_username = $('#about_username').val();
                            var about_state = $('#about_state').val();
                            
                            $('#about-username-value').html(about_username ? about_username : 'user<?= $data['user']['id'] ?>');
                            $('#about-state-value').html(about_state);
                            
                            $('#view-basic').toggle();
                            $('#form-basic').toggle();
                        }
                    });
                });
                
                $('#form-contact').submit(function(event) {
                    event.preventDefault();

                    $(this).find('[type="submit"]').html('Подождите...').attr('disabled', true);
                    $(this).find('.toggle-contact').hide();
                    
                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/users/api/about/update.json',
                        data: $(this).serialize(),
                        success: function() {
                            swal({
                                title: 'Контактная информация была обновлена',
                                type: 'success',
                                timer: 2500,
                                showConfirmButton: false
                            });

                            $('#form-contact').find('[type="submit"]').html('Сохранить').attr('disabled', false);
                            $('#form-contact').find('.toggle-contact').show();
                            
                            var about_mobile_phone = $('#about_mobile_phone').val();
                            var about_twitter = $('#about_twitter').val();
                            var about_skype = $('#about_skype').val();
                            
                            $('#about-mobile-phone-value').html(about_mobile_phone ? about_mobile_phone : 'нет');
                            $('#about-twitter-value').html(about_twitter ? about_twitter : 'нет');
                            $('#about-skype-value').html(about_skype ? about_skype : 'нет');
                            
                            $('#view-contact').toggle();
                            $('#form-contact').toggle();
                        }
                    });
                });
                
                var mail_events = <?= json_encode($data['mail']) ?>;
                
                $('body').on('click', '.mail_events', function() {
                    var id = $(this).data('id');
                    
                    $('#mail-events-modal .details .mail_id').html(mail_events[id].log.id);
                    $('#mail-events-modal .details .mail_state').html(mail_events[id].log.state);
                    $('#mail-events-modal .details .mail_result').html(mail_events[id].log.result);
                    $('#mail-events-modal .details .mail_retries').html(mail_events[id].log.retries);
                    $('#mail-events-modal .details .mail_ping').html(mail_events[id].log.ping);
                    $('#mail-events-modal .details .mail_cr_date').html(mail_events[id].log.cr_date);
                    $('#mail-events-modal .details .mail_letter_subject').html(mail_events[id].log.letter_subject);
                    $('#mail-events-modal .details .mail_letter_priority').html(mail_events[id].log.letter_priority);
                    $('#mail-events-modal .details .mail_sender_name').html(mail_events[id].log.sender_name);
                    $('#mail-events-modal .details .mail_sender_email').html(mail_events[id].log.sender_email);
                    $('#mail-events-modal .details .mail_transport_module').html(mail_events[id].log.transport_module);
                    $('#mail-events-modal .details .mail_transport_method').html(mail_events[id].log.transport_method);
                    
                    $('#mail-events-modal .events').empty();
                    
                    if (mail_events[id].events.length) {
                        $.each(mail_events[id].events, function(key, event) {
                            var details = event.details !== 'NULL' ? JSON.stringify(JSON.parse(event.details), undefined, 4) : 'Details not found';

                            $('#mail-events-modal .events').append([
                                '<div class="panel panel-collapse">',
                                    '<div class="panel-heading" role="tab" id="heading-mail-event-' + event.id + '">',
                                        '<h4 class="panel-title">',
                                            '<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-mail-event-' + event.id + '" aria-expanded="false" aria-controls="collapse-mail-event-' + event.id + '"><span class="pull-right">' + event.cr_date + '</span>' + event.event + '</a>',
                                        '</h4>',
                                    '</div>',
                                    '<div id="collapse-mail-event-' + event.id + '" class="collapse" role="tabpanel" aria-labelledby="collapse-mail-event-' + event.id + '">',
                                        '<div class="panel-body"><pre>' + details + '</pre></div>',
                                    '</div>',
                                '</div>'
                            ].join(''));
                        });
                    } else {
                        $('#mail-events-modal .events').html('<div class="alert alert-warning" role="alert">События не найдены</div>');
                    }
                    
                    $('#mail-events-modal').modal('show');
                });
                
                var tunnel_tags = <?= json_encode($data['tunnels']) ?>;
                
                $('body').on('click', '.tunnel_tags', function() {
                    var id = $(this).data('id');
                    
                    $('#tunnel-tags-modal .details .tunnel_uid').html(tunnel_tags[id].info.id);
                    $('#tunnel-tags-modal .details .tunnel_state').html(tunnel_tags[id].info.state);
                    $('#tunnel-tags-modal .details .tunnel_type').html(tunnel_tags[id].info.tunnel_type);
                    $('#tunnel-tags-modal .details .tunnel_id').html(tunnel_tags[id].info.tunnel_id);
                    $('#tunnel-tags-modal .details .tunnel_name').html(tunnel_tags[id].info.tunnel_name);
                    
                    $('#tunnel-tags-modal .tags').empty();
                    
                    if (tunnel_tags[id].tags.length) {
                        $.each(tunnel_tags[id].tags, function(key, tag) {
                            console.log(tag);
                            var info = tag.info !== 'NULL' ? JSON.stringify(JSON.parse(tag.info), undefined, 4) : 'Подробная информация отсутствует';

                            $('#tunnel-tags-modal .tags').append([
                                '<div class="panel panel-collapse">',
                                    '<div class="panel-heading" role="tab" id="heading-mail-event-' + tag.id + '">',
                                        '<h4 class="panel-title">',
                                            '<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-mail-event-' + tag.id + '" aria-expanded="false" aria-controls="collapse-mail-event-' + tag.id + '"><span class="pull-right">' + tag.cr_date + '</span>' + tag.label_id + '</a>',
                                        '</h4>',
                                    '</div>',
                                    '<div id="collapse-mail-event-' + tag.id + '" class="collapse" role="tabpanel" aria-labelledby="collapse-mail-event-' + tag.id + '">',
                                        '<div class="panel-body"><pre>' + info + '</pre></div>',
                                    '</div>',
                                '</div>'
                            ].join(''));
                        });
                    } else {
                        $('#tunnel-tags-modal .tags').html('<div class="alert alert-warning" role="alert">События не найдены</div>');
                    }
                    
                    $('#tunnel-tags-modal').modal('show');
                });
                
                var tags = <?= json_encode($data['tags']) ?>;
                
                $('body').on('click', '.tags', function() {
                    var id = $(this).data('id');
                    var value = tags[id].value !== 'NULL' ? JSON.stringify(JSON.parse(tags[id].value), undefined, 4) : 'Подробная информация отсутствует';
                    
                    
                    $('#tags-modal .details .tag_id').html(tags[id].id);
                    $('#tags-modal .details .tag_item').html(tags[id].item);
                    $('#tags-modal .details .tag_value pre').html(tags[id].value);
                    $('#tags-modal .details .tag_cr_date').html(tags[id].cr_date);

                    $('#tags-modal').modal('show');
                });
            });
        </script>
    </body>
</html>