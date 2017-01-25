<?
$return = $data['return'] ? $data['return'] : APP::Module('Routing')->root . 'users/profile';
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Личный кабинет</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">

        <? APP::Render('core/widgets/css') ?>
    </head>
    <body>
        <div class="l-block">
            <a data-block="#l-login" data-bg="teal" class="palette-Blue text d-block actions"></a>
            <a data-block="#l-register" data-bg="blue" class="palette-Teal text d-block actions"></a>
            <a data-block="#l-forgot-password" data-bg="purple" class="palette-Teal text d-block actions"></a>
            <a data-block="#l-change-password" data-bg="blue" class="palette-Teal text d-block actions"></a>
        </div>

        <div class="login" data-lbg="teal">
            <!-- Login -->
            <div class="l-block" id="l-login">
                <div class="lb-header palette-Teal bg">
                    <i class="zmdi zmdi-account-circle"></i>
                    Пожалуйста, войдите
                </div>
                <div class="lb-body">
                    <?
                    if ($data['return']) {
                        ?><div class="alert alert-warning" role="alert"><b>Вы пытаетесь получить доступ к защищенной странице</b></div><?
                    }

                    if ((int) APP::Module('Users')->settings['module_users_login_service']) {
                        ?>
                        <form id="login">
                            <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_vk_id'])) { ?><a class="m-b-25 m-r-5 m-t-5 btn btn-default btn-icon waves-effect waves-circle waves-float" href="http://oauth.vk.com/authorize?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_vk_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/vk', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode($return) . '"}')])) ?>"><i class="zmdi zmdi-vk"></i></a><? } ?>
                            <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_fb_id'])) { ?><a class="m-b-25 m-r-5 m-t-5 btn btn-default btn-icon waves-effect waves-circle waves-float" href="https://www.facebook.com/dialog/oauth?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_fb_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/fb', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode($return) . '"}')])) ?>"><i class="zmdi zmdi-facebook"></i></a><? } ?>
                            <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_google_id'])) { ?><a class="m-b-25 m-r-5 m-t-5 btn btn-default btn-icon waves-effect waves-circle waves-float" href="https://accounts.google.com/o/oauth2/auth?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_google_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/google', 'response_type' => 'code', 'scope' => urlencode('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'), 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode($return) . '"}')])) ?>"><i class="zmdi zmdi-google-plus-box"></i></a><? } ?>
                            <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_ya_id'])) { ?><a class="m-b-25 m-r-5 m-t-5 btn btn-default btn-icon waves-effect waves-circle waves-float" href="https://oauth.yandex.ru/authorize?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_ya_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/ya', 'response_type' => 'code', 'display' => 'popup', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode($return) . '"}')])) ?>"><i class="zmdi zmdi-twitter"></i></a><? } ?>

                            <div class="form-group fg-float">
                                <div class="fg-line">
                                    <input type="email" name="email" id="email" class="input-sm form-control fg-input">
                                    <label class="fg-label">Email</label>
                                </div>
                            </div>

                            <div class="form-group fg-float">
                                <div class="fg-line">
                                    <input type="password" name="password" id="password" class="input-sm form-control fg-input">
                                    <label class="fg-label">Пароль</label>
                                </div>
                            </div>

                            <div class="checkbox m-b-30">
                                <label>
                                    <input type="checkbox" name="remember-me" checked="checked">
                                    <i class="input-helper"></i>
                                    Запомнить меня
                                </label>
                            </div>

                            <button type="submit" class="btn palette-Teal bg">Войти</button>
                        </form>
                        <?
                    } else {
                        ?><div class="alert alert-danger" role="alert"><i class="zmdi zmdi-close-circle zmdi-hc-fw"></i><b>Функция входа отключена</b></div><?
                    }
                    ?>

                    <div class="m-t-20">
                        <a data-block="#l-forgot-password" data-bg="purple" href="" class="palette-Teal text">Забыли пароль?</a>
                        <a data-block="#l-register" data-bg="blue" class="palette-Teal text d-block m-b-5" href="">Пройти регистрацию</a>
                    </div>
                </div>
            </div>

            <!-- Register -->
            <div class="l-block" id="l-register">
                <div class="lb-header palette-Blue bg">
                    <i class="zmdi zmdi-account-add"></i>
                    Регистрация
                </div>

                <div class="lb-body">
                    <?
                    if ((int) APP::Module('Users')->settings['module_users_register_service']) {
                        ?>
                        <form id="register">
                            <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_vk_id'])) { ?><a class="m-b-25 m-r-5 m-t-5 btn btn-default btn-icon waves-effect waves-circle waves-float" href="http://oauth.vk.com/authorize?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_vk_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/vk', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode($return) . '"}')])) ?>"><i class="zmdi zmdi-vk"></i></a><? } ?>
                            <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_fb_id'])) { ?><a class="m-b-25 m-r-5 m-t-5 btn btn-default btn-icon waves-effect waves-circle waves-float" href="https://www.facebook.com/dialog/oauth?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_fb_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/fb', 'response_type' => 'code', 'scope' => 'email', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode($return) . '"}')])) ?>"><i class="zmdi zmdi-facebook"></i></a><? } ?>
                            <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_google_id'])) { ?><a class="m-b-25 m-r-5 m-t-5 btn btn-default btn-icon waves-effect waves-circle waves-float" href="https://accounts.google.com/o/oauth2/auth?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_google_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/google', 'response_type' => 'code', 'scope' => urlencode('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'), 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode($return) . '"}')])) ?>"><i class="zmdi zmdi-google-plus-box"></i></a><? } ?>
                            <? if (!empty(APP::Module('Users')->settings['module_users_oauth_client_ya_id'])) { ?><a class="m-b-25 m-r-5 m-t-5 btn btn-default btn-icon waves-effect waves-circle waves-float" href="https://oauth.yandex.ru/authorize?<?= urldecode(http_build_query(['client_id' => APP::Module('Users')->settings['module_users_oauth_client_ya_id'], 'redirect_uri'  => APP::Module('Routing')->root . 'users/login/ya', 'response_type' => 'code', 'display' => 'popup', 'state' => APP::Module('Crypt')->SafeB64Encode('{"return": "' . APP::Module('Crypt')->Encode($return) . '"}')])) ?>"><i class="zmdi zmdi-twitter"></i></a><? } ?>

                            <div class="form-group fg-float">
                                <div class="fg-line">
                                    <input type="email" name="email" id="email" class="input-sm form-control fg-input">
                                    <label class="fg-label">Email</label>
                                </div>
                            </div>

                            <div class="form-group fg-float">
                                <div class="fg-line">
                                    <input type="password" name="password" id="password" class="input-sm form-control fg-input">
                                    <label class="fg-label">Пароль</label>
                                </div>
                            </div>

                            <div class="form-group fg-float">
                                <div class="fg-line">
                                    <input type="password" name="re-password" id="re-password" class="input-sm form-control fg-input">
                                    <label class="fg-label">Повторите пароль</label>
                                </div>
                            </div>

                            <button type="submit" class="btn palette-Blue bg">Пройти регистрацию</button>
                        </form>
                        <?
                    } else {
                        ?><div class="alert alert-danger" role="alert"><i class="zmdi zmdi-close-circle zmdi-hc-fw"></i><b>Регистрация пользователей отключена</b></div><?
                    }
                    ?>

                    <div class="m-t-30">
                        <a data-block="#l-login" data-bg="teal" class="palette-Blue text d-block m-b-5" href="">Уже зарегистрированы?</a>
                        <a data-block="#l-forgot-password" data-bg="purple" href="" class="palette-Blue text">Забыли пароль?</a>
                    </div>
                </div>
            </div>

            <!-- Forgot Password -->
            <div class="l-block" id="l-forgot-password">
                <div class="lb-header palette-Purple bg">
                    <i class="zmdi zmdi-mood-bad"></i>
                    Забыли пароль?
                </div>

                <div class="lb-body">
                    <?
                    if ((int) APP::Module('Users')->settings['module_users_reset_password_service']) {
                        ?>
                        <form id="reset-password">
                            <p class="m-b-30">Мы отправим вам ссылку для установки нового пароля.</p>

                            <div class="form-group fg-float">
                                <div class="fg-line">
                                    <input type="text" name="email" id="email" class="input-sm form-control fg-input">
                                    <label class="fg-label">Email</label>
                                </div>
                            </div>

                            <button type="submit" class="btn palette-Purple bg">Восстановить пароль</button>
                        </form>
                        <?
                    } else {
                        ?><div class="alert alert-danger" role="alert"><i class="zmdi zmdi-close-circle zmdi-hc-fw"></i><b>Восстановление пароля отключено</b></div><?
                    }
                    ?>

                    <div class="m-t-30">
                        <a data-block="#l-login" data-bg="teal" class="palette-Purple text d-block m-b-5" href="">Уже зарегистрированы?</a>
                        <a data-block="#l-register" data-bg="blue" href="" class="palette-Purple text">Пройти регистрацию</a>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <?
            if (isset(APP::Module('Users')->user['email'])) {
                ?>
                <div class="l-block" id="l-change-password">
                    <div class="lb-header palette-Blue bg">
                        <img src="<?= APP::$conf['location'][0] ?>://www.gravatar.com/avatar/<?= md5(APP::Module('Users')->user['email']) ?>?s=40&d=<?= urlencode(APP::Module('Routing')->root . APP::Module('Users')->settings['module_users_profile_picture']) ?>&t=<?= time() ?>" class="avatar-img">
                        Изменение пароля
                    </div>

                    <div class="lb-body">
                        <?
                        if ((int) APP::Module('Users')->settings['module_users_change_password_service']) {
                            ?>
                            <form id="change-password">
                                <div class="form-group fg-float">
                                    <div class="fg-line">
                                        <input type="password" name="password" id="password" class="input-sm form-control fg-input">
                                        <label class="fg-label">Новый пароль</label>
                                    </div>
                                </div>

                                <div class="form-group fg-float">
                                    <div class="fg-line">
                                        <input type="password" name="re-password" id="re-password" class="input-sm form-control fg-input">
                                        <label class="fg-label">Повторите пароль</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn palette-Blue bg">Изменить пароль</button>
                            </form>
                            <?
                        } else {
                            ?><div class="alert alert-danger" role="alert"><i class="zmdi zmdi-close-circle zmdi-hc-fw"></i><b>Изменение паролей отключено</b></div><?
                        }
                        ?>
                    </div>
                </div>
                <?
            }
            ?>
        </div>

        <? APP::Render('core/widgets/ie_warning') ?>

        <!-- Javascript Libraries -->
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>

        <? APP::Render('core/widgets/js') ?>

        <script>
            $(document).ready(function() {
                if ($('.login')[0]) {
                    $('body').on('click', '.l-block [data-block]', function(e){
                        e.preventDefault();

                        var z = $(this).data('block');
                        var t = $(this).closest('.l-block');
                        var c = $(this).data('bg');

                        t.removeClass('toggled');

                        setTimeout(function(){
                            $('.login').attr('data-lbg', c);
                            $(z).addClass('toggled');
                        });

                    });
                }

                $('.l-block [data-block="#l-<?= APP::Module('Routing')->get['action'] ?>"]').trigger('click');

                $('#login').submit(function(event) {
                    event.preventDefault();

                    var email = $(this).find('#email');
                    var password = $(this).find('#password');

                    email.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    password.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (email.val() === '') { email.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Не указан</small>'); return false; }
                    if (password.val() === '') { password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Не указан</small>'); return false; }

                    $(this).find('[type="submit"]').html('Вход...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>users/api/login.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch (result.status) {
                                case 'error': 
                                    swal({
                                        title: 'Ошибка',
                                        text: 'Неверные данные для входа',
                                        type: 'error',
                                        timer: 2500,
                                        showConfirmButton: false
                                    });
                                    break;
                                case 'success': window.location.href = '<?= $return ?>'; break;
                            }

                            $('#login').find('[type="submit"]').html('Войти').attr('disabled', false);
                        }
                    });
                });

                $('#register').submit(function(event) {
                    event.preventDefault();

                    var email = $(this).find('#email');
                    var password = $(this).find('#password');
                    var re_password = $(this).find('#re-password');

                    email.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    password.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    re_password.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (email.val() === '') { email.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Не указан</small>'); return false; }
                    if (password.val() === '') { password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Не указан</small>'); return false; }
                    if (password.val() !== re_password.val()) { re_password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Пароли не совпадают</small>'); return false; }

                    $(this).find('[type="submit"]').html('Регистрация...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>users/api/register.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Готово!',
                                        text: 'Вы успешно прошли регистрацию',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: false
                                    }, function(){
                                        window.location.href = '<?= $return ?>';
                                    });
                                    break;
                                case 'error':
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {
                                            case 1: email.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Неверный Email</small>'); break;
                                            case 2: email.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">E-Mail уже зарегистрирован</small>'); break;
                                            case 3: password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); break;
                                            case 4: password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Пароль слишком короткий</small>'); break;
                                            case 5: password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Пароли не совпадают</small>'); break;
                                            case 6: alert('Service turned off'); break;
                                        }
                                    });
                                    break;
                            }

                            $('#register').find('[type="submit"]').html('Пройти регистрацию').attr('disabled', false);
                        }
                    });
                  });

                $('#reset-password').submit(function(event) {
                    event.preventDefault();

                    var email = $(this).find('#email');
                    email.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (email.val() === '') { email.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Не введен</small>'); return false; }

                    $(this).find('[type="submit"]').html('Подождите...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>users/api/reset-password.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Готово!',
                                        text: 'На ваш E-Mail была отправлена ссылка для установки нового пароля',
                                        type: 'success',
                                        showConfirmButton: false
                                    });
                                    break;
                                case 'error':
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {
                                            case 1: email.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Неверный E-Mail</small>'); break;
                                            case 2: email.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">E-Mail не найден</small>'); break;
                                            case 3: alert('Восстановление паролей отключено'); break;
                                        }
                                    });
                                    break;
                            }

                            $('#reset-password').find('[type="submit"]').html('Восстановить пароль').attr('disabled', false);
                        }
                    });
                  });

                $('#change-password').submit(function(event) {
                    event.preventDefault();

                    var password = $(this).find('#password');
                    var re_password = $(this).find('#re-password');

                    password.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    re_password.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (password.val() === '') { password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Не введен</small>'); return false; }
                    if (password.val() !== re_password.val()) { re_password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Пароли не совпадают</small>'); return false; }

                    $(this).find('[type="submit"]').html('Подождите...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>users/api/change-password.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Готово!',
                                        text: 'Пароль был успешно изменен',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: false
                                    }, function(){
                                        window.location.href = '<?= $return ?>';
                                    });
                                    break;
                                case 'error':
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {
                                            case 1: alert('Вы должны войти'); break;
                                            case 2: password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Не введен</small>'); break;
                                            case 3: password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Пароль слишком короткий</small>'); break;
                                            case 4: password.closest('.form-group').addClass('has-error has-feedback').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Пароли не совпадают</small>'); break;
                                            case 5: alert('Service turned off'); break;
                                        }
                                    });
                                    break;
                            }

                            $('#register').find('[type="submit"]').html('Изменить пароль').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
</html>