<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Pult process editor">
        <meta name="author" content="Selivanov Max <max@evildevel.com>">

        <title>Tunnel editor</title>

        <!-- Bootstrap core CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- Bootstrap core CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/bootstrap-3.3.6-dist/css/non-responsive.css" rel="stylesheet">
        
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/ie-emulation-modes-warning.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <link href="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/jquery-ui.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/processes-selector/style.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/letter-selector/style.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/letter-sender/style.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/rules-editor/style.css" rel="stylesheet">
        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        
        <style>
            .popover {
                width: 400px;
                max-width: 400px;
            }
            
            
            #process-editor {
                
            }
            #header {
                position: fixed;
                z-index: 100;
                width: 100%;
                height: 50px;
                background-color: #303030;
                padding: 14px;
                font-weight: bold;
            }
            .all-comments.checked {
                background-color: #ababab;
            }
            .all-info.checked {
                background-color: #ababab;
            }
            #scheme-wrapper {
                position: absolute;
                left: 0px;
                top: 50px;
                width: 100%;
            }
            #svg {
                display: none;
                position: absolute;
                z-index: 10;
                width: 100%;
                background: #F9F9F9;
                top: 0;
                left: 0;
            }
            #svg > .connector {
                stroke: rgb(255, 165, 0); 
                stroke-width: 4;
                cursor: pointer;
            }
            #svg > .connector:hover {
                stroke: rgb(255, 0, 0); 
                stroke-width: 4;
            }
            #objects {
                display: none;
                position: relative;
                z-index: 50;
                width: 100%;
                top: 0;
                left: 0;
            }
            
            .object {
                position: absolute !important;
                display: inline-block;
                text-align: center;
                cursor: move;
                color: #FFF;
                font-weight: bold;
                box-shadow: 0px 2px 1px rgba(0,0,0,0.5);
            }
            .object:hover {
                box-shadow: 0px 3px 2px rgba(0,0,0,0.5);
            }
            .object > .connector {
                display: none;
                position: absolute;
                width: 26px;
                height: 26px;
                background: #ffffff;
                background-image: url(<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/link.png);
                background-position: center;
                background-repeat: no-repeat;
                border-radius: 13px;
                border: 2px solid;
                cursor: pointer;
                z-index: 100;
                background-size: 22px;
            }
            .object > .id {
                display: none;
                position: absolute;
                z-index: 8090;
                height: 20px;
                background: #FFFFFF;
                font-size: 12px;
                color: #000000;
                padding: 2px 5px;
                box-shadow: 0px 1px 1px rgba(0,0,0,0.5);
                border-radius: 3px;
                border: 1px solid;
            }
            .object > .users-count {
                position: absolute;
                z-index: 8090;
                height: 20px;
                background: #FFFFFF;
                font-size: 12px;
                color: #000000;
                padding: 2px 7px;
                box-shadow: 0px 1px 1px rgba(0,0,0,0.5);
                border-radius: 10px;
                border: 1px solid;
                white-space: nowrap;
            }
            .object a {
                color: #000;
            }
            .object > .comment {
                display: none;
                position: absolute;
                width: 20px;
                height: 20px;
                background: #fefe22;
                border-radius: 13px;
                border: 2px solid #000;
                cursor: pointer;
                color: #000;
                box-shadow: 0px 0px 8px 2px rgba(0,0,0,0.75);
            }
            .object > .comment > span {
                display: block;
                position: absolute;
                top: 5px;
                left: 5px;
                font-size: 12px;
                line-height: 8px;
            }
            .object.action {
                background-color: #8BC34A;
                width: 200px;
                height: 100px;
                -moz-border-radius: 8px;
                -webkit-border-radius: 8px;
                border-radius: 8px;
                font-size: 50px;
                padding: 21px;
            }
            .object.action > .users-count {
                top: -10px;
                left: -10px;
                border-color: #8BC34A;
            }
            .object.action > .id {
                top: -10px;
                right: -15px;
                border-color: #8BC34A;
            }
            .object.action > .connector {
                border-color: #8BC34A;
            }
            .object.action > .connector.parent {
                left: 88px;
                top: -13px;
                box-shadow: 0px 1px 1px rgba(0,0,0,0.5);
            }
            .object.action > .connector.child {
                left: 88px;
                top: 87px;
                box-shadow: 0px -1px 1px rgba(0,0,0,0.5);
            }
            .object.action > .comment {
                right: -10px;
                top: 90px;
                border-color: #8BC34A;
                height: 20px;
                background: #FFFFFF;
                font-size: 12px;
                color: #000000;
                padding: 2px 7px;
                box-shadow: 0px 1px 1px rgba(0,0,0,0.5);
                border-radius: 10px;
                border: 1px solid;
                white-space: nowrap;
            }
            .object.action > .action_icon {
                font-size: 20px;
                position: absolute;
                top: 15px;
                left: 10px;
            }
            .object.action > .action_details {
                position: absolute;
                left: 40px;
                top: 15px;
                width: 150px;
                height: 75px;
                font-size: 11px;
                font-weight: 400;
                text-align: left;
                overflow: hidden;
            }
            .object.action > .send_mail_info {
                position: absolute;
                left: 40px;
                top: 15px;
                width: 150px;
                height: 0;
                font-size: 11px;
                line-height: 13px;
                font-weight: 400;
                text-align: left;
                overflow: hidden;
                background-color: #8BC34A;
                transition: height 0.3s ease-out;
            }
            .object.action:hover > .send_mail_info {
                height: 80px;
                transition: height 0.3s ease-in;
            }
            .object.condition {
                width: 260px;
                height: 100px;
                line-height: 80px;
                background-color: #2196F3;
                -moz-border-radius: 40px;
                -webkit-border-radius: 40px;
                border-radius: 50px;
                font-size: 26px;
            }
            .object.condition > .id {
                top: -10px;
                right: 20px;
                border-color: #2196F3;
                line-height: 17px;
            }
            .object.condition > .users-count {
                top: -10px;
                left: 20px;
                border-color: #2196F3;
                line-height: 17px;
            }
            .object.condition > .connector {
                border-color: #2196F3;
            }
            .object.condition > .connector.parent {
                left: 119px;
                top: -13px;
                box-shadow: 0px 1px 1px rgba(0,0,0,0.5);
            }
            .object.condition > .connector.child.y {
                left: -13px;
                top: 38px;
                box-shadow: 1px 0px 1px rgba(0,0,0,0.5);
            }
            .object.condition > .connector.child.n {
                right: -13px;
                top: 38px;
                box-shadow: -1px 0px 1px rgba(0,0,0,0.5);
            }
            .object.condition > .comment {
                right: 25px;
                bottom: -10px;
                border-color: #2196F3;
            }
            .object.condition > .condition_title {
                font-size: 14px;
                line-height: 20px;
                position: absolute;
                width: 100%;
                top: 10px;
            }
            .object.condition > .condition_details {
                width: 207px;
                height: 58px;
                position: absolute;
                top: 30px;
                left: 27px;
                font-size: 11px;
                line-height: 14px;
                text-align: left;
                overflow: hidden;
            }
            .object.timeout {
                background-color: #FF9800;
                width: 100px;
                height: 100px;
                -moz-border-radius: 50px;
                -webkit-border-radius: 50px;
                border-radius: 50px;
                padding: 20px;
            }
            .object.timeout > .id {
                top: 0px;
                left: 75px;
                border-color: #FF9800;
            }
            .object.timeout > .users-count {
                top: 0px;
                right: 72px;
                border-color: #FF9800;
            }
            .object.timeout > .connector {
                border-color: #FF9800;
            }
            .object.timeout > .connector.parent {
                left: 37px;
                top: -13px;
                box-shadow: 0px 1px 1px rgba(0,0,0,0.5);
            }
            .object.timeout > .connector.child {
                left: 37px;
                top: 87px;
                box-shadow: 0px -1px 1px rgba(0,0,0,0.5);
            }
            .object.timeout > .glyphicon {
                font-size: 38px;
                display: block;
            }
            .object.timeout > .value {
                margin-right: 5px;
            }
            .object.timeout > .comment {
                right: -10px;
                top: 40px;
                border-color: #FF9800;
            }
            .comment-tooltip {
                width: 250px;
                background-color: rgba(0,0,0,0.75);
                position: absolute;
                left: 18px;
                top: 18px;
                display: none;
                font-size: 13px;
                color: #fff;
                line-height: 15px;
                font-weight: 400;
                padding: 5px;
                text-align: left;
                z-index: 9000;
            }
            .info-tooltip {
                display: none;
                width: 150px;
                background-color: rgba(0,0,0,0.75);
                position: absolute;
                top: 9px;
                right: -155px;
                font-size: 11px;
                line-height: 13px;
                color: #fff;
                line-height: 15px;
                font-weight: 400;
                padding: 5px;
                text-align: left;
                z-index: 9000;
            }
        </style>
    </head>
    <body>
        <div id="process-editor">
            <div id="header">
                <input type="hidden" id="open_process">
            </div>
            <div id="scheme-wrapper">
                <svg id="svg">
                    <defs>
                        <pattern id="smallGrid" width="20" height="20" patternUnits="userSpaceOnUse">
                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="gray" stroke-width="0.5"/>
                        </pattern>
                        <pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse">
                            <rect width="100" height="100" fill="url(#smallGrid)"/>
                            <path d="M 100 0 L 0 0 0 100" fill="none" stroke="gray" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
                <div id="objects"></div>
            </div>
        </div>

        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/jquery-ui.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/jquery.json.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/ie10-viewport-bug-workaround.js"></script>
        
        <!--<script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/processes-selector/script.js"></script>-->
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/letter-selector/script.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/letter-sender/script.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/tunnel-selector/script.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/rules-editor/script.js"></script>
        
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
        
        <script>
            TunnelEditor = {
                API: {
                    Scheme: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/scheme.json',
                    SchemeUpdate: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/update.json',
                    Actions: {
                        Create: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/actions/create.json',
                        Update: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/actions/update.json',
                        Remove: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/actions/remove.json'
                    },
                    Conditions: {
                        Create: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/conditions/create.json',
                        Update: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/conditions/update.json',
                        Remove: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/conditions/remove.json'
                    },
                    Timeouts: {
                        Create: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/timeouts/create.json',
                        Update: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/timeouts/update.json',
                        Remove: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/timeouts/remove.json'
                    },
                    GetLetters: '<?= APP::Module('Routing')->root ?>admin/mail/api/letters/get.json',
                    GetSenders: '<?= APP::Module('Routing')->root ?>admin/mail/api/senders/get.json'
                },
                Tunnel: {},
                Objects: {
                    Actions: {},
                    Conditions: {},
                    Timeouts: {}
                },
                Connector: false,
                Scale: 1,
                SetScale: function () {
                    // $('#scheme-wrapper').css('zoom', TunnelEditor.Scale);
                    $('#scheme-wrapper').css('transform', 'scale(' + TunnelEditor.Scale + ', ' + TunnelEditor.Scale + ')');
                    $('.scaling-default').text(Math.floor(TunnelEditor.Scale * 100) + '%');
                },
                Init: function(id) {
                    $('#objects').empty();
                    $('#svg .connector').remove();
                    $('#header > .btn-group.pull-right').remove();
                    
                    TunnelEditor.Tunnel = {};
                    TunnelEditor.Tunnel.Objects = {
                        Actions: {},
                        Conditions: {},
                        Timeouts: {}
                    };
                    TunnelEditor.Connector = false;
                    
                    TunnelEditor.Tunnel = id;
                    TunnelEditor.RenderHeader();
                    TunnelEditor.LoadTunnel();
                },
                RenderHeader: function() {
                    $('#header')
                    .append(
                        $('<div/>', {
                            class: 'btn-group pull-right',
                            role: 'group'
                        })
                        .append(
                            $('<div/>', {
                                class: 'btn-group'
                            })
                            .append(
                                $('<button/>', {
                                    type: 'button',
                                    class: 'btn btn-default btn-xs dropdown-toggle',
                                    'data-toggle': 'dropdown'
                                })
                                .append('Действие')
                            )
                            .append(
                                $('<ul/>', {
                                    class: 'dropdown-menu'
                                })
                                .append(
                                    $('<li/>')
                                    .append(
                                        $('<a/>', {
                                            href: 'javascript:void(0);'
                                        })
                                        .append('Отправка письма')
                                        .on('click', function() {
                                            TunnelEditor.AddAction({
                                                tunnel_id: TunnelEditor.Tunnel.id,
                                                action: 'send_mail',
                                                settings: {},
                                                child_object: [],
                                                style: {
                                                    top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                                    left: 10
                                                },
                                                'comment':'Выполнение отправки письма'
                                            });
                                        })
                                    )
                                )
                                .append(
                                    $('<li/>')
                                    .append(
                                        $('<a/>', {
                                            href: 'javascript:void(0);'
                                        })
                                        .append('Подписка на туннель')
                                        .on('click', function() {
                                            TunnelEditor.AddAction({
                                                tunnel_id: TunnelEditor.Tunnel.id,
                                                action: 'subscribe',
                                                settings: {},
                                                child_object: [],
                                                style: {
                                                    top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                                    left: 10
                                                },
                                                'comment':'Выполнение подписки на туннель'
                                            });
                                        })
                                    )
                                )
                                .append(
                                    $('<li/>')
                                    .append(
                                        $('<a/>', {
                                            href: 'javascript:void(0);'
                                        })
                                        .append('Случайная подписка на туннель')
                                        .on('click', function() {
                                            TunnelEditor.AddAction({
                                                tunnel_id: TunnelEditor.Tunnel.id,
                                                action: 'random_subscribe',
                                                settings: {
                                                    processes: []
                                                },
                                                child_object: [],
                                                style: {
                                                    top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                                    left: 10
                                                },
                                                'comment':'Выбор случайного туннеля из списка и подписка на него'
                                            });
                                        })
                                    )
                                )
                                .append(
                                    $('<li/>')
                                    .append(
                                        $('<a/>', {
                                            href: 'javascript:void(0);'
                                        })
                                        .append('Снятие туннеля с паузы')
                                        .on('click', function() {
                                            TunnelEditor.AddAction({
                                                tunnel_id: TunnelEditor.Tunnel.id,
                                                action: 'activate_process',
                                                settings: {},
                                                child_object: [],
                                                style: {
                                                    top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                                    left: 10
                                                },
                                                'comment':'Указанный туннель устанавливается состояние "активный" если имеется подписка на него и он находятся на паузе'
                                            });
                                        })
                                    )
                                )
                                .append(
                                    $('<li/>')
                                    .append(
                                        $('<a/>', {
                                            href: 'javascript:void(0);'
                                        })
                                        .append('Прохождение туннеля')
                                        .on('click', function() {
                                            TunnelEditor.AddAction({
                                                tunnel_id: TunnelEditor.Tunnel.id,
                                                action: 'auto_complete',
                                                settings: {},
                                                child_object: [],
                                                style: {
                                                    top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                                    left: 10
                                                },
                                                'comment':'Если имеется подписка на туннель в состоянии отличном от "пройденный", то данный туннель помечается как "пройденный. Если подписки на целевой туннель нет, то создается подписка на туннель и автоматически завершается (отмечается как "пройденный").'
                                            });
                                        })
                                    )
                                )
                                .append(
                                    $('<li/>')
                                    .append(
                                        $('<a/>', {
                                            href: 'javascript:void(0);'
                                        })
                                        .append('Возврат в точку подписки')
                                        .on('click', function() {
                                            TunnelEditor.AddAction({
                                                tunnel_id: TunnelEditor.Tunnel.id,
                                                action: 'recycle_process',
                                                settings: {},
                                                child_object: [],
                                                style: {
                                                    top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                                    left: 10
                                                },
                                                'comment':'Пользователь позвращается в точку начала подписки на туннель'
                                            });
                                        })
                                    )
                                )
                                .append(
                                    $('<li/>')
                                    .append(
                                        $('<a/>', {
                                            href: 'javascript:void(0);'
                                        })
                                        .append('Завершение туннеля')
                                        .on('click', function() {
                                            TunnelEditor.AddAction({
                                                tunnel_id: TunnelEditor.Tunnel.id,
                                                action: 'complete',
                                                settings: {},
                                                child_object: [],
                                                style: {
                                                    top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                                    left: 10
                                                },
                                                'comment':'Перевод текущего туннеля в состояние "пройденный"'
                                            });
                                        })
                                    )
                                )
                                .append(
                                    $('<li/>')
                                    .append(
                                        $('<a/>', {
                                            href: 'javascript:void(0);'
                                        })
                                        .append('Установка/обновление метки пользователя')
                                        .on('click', function() {
                                            TunnelEditor.AddAction({
                                                tunnel_id: TunnelEditor.Tunnel.id,
                                                action: 'set_user_tag',
                                                settings: {},
                                                child_object: [],
                                                style: {
                                                    top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                                    left: 10
                                                },
                                                'comment':'Создание метки пользователя. Если метка есть, то значение обновляется.'
                                            });
                                        })
                                    )
                                )
                            )
                        )
                        .append(
                            $('<button/>', {
                                type: 'button',
                                class: 'btn btn-default btn-xs'
                            })
                            .append('Условие')
                            .on('click', function() {
                                TunnelEditor.AddCondition({
                                    tunnel_id: TunnelEditor.Tunnel.id,
                                    rules: {
                                       logic: 'intersect'
                                    },
                                    child_object_y: [],
                                    child_object_n: [],
                                    style: {
                                        top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                        left: 10
                                    },
                                    'comment':''
                                });
                            })
                        )
                        .append(
                            $('<button/>', {
                                type: 'button',
                                class: 'btn btn-default btn-xs'
                            })
                            .append('Таймер')
                            .on('click', function() {
                                TunnelEditor.AddTimeout({
                                    tunnel_id: TunnelEditor.Tunnel.id,
                                    timeout: 1,
                                    timeout_type: 'days',
                                    child_object: [],
                                    style: {
                                        top: Math.ceil(($(window).scrollTop() + 10) / 10) * 10,
                                        left: 10
                                    },
                                    'comment':''
                                });
                            })
                        )
                    )
                    .append(
                        $('<div/>', {
                            class: 'btn-group pull-right',
                            role: 'group'
                        })
                        .css({
                            'margin-right': '15px'
                        })
                        .append(
                            $('<button/>', {
                                type: 'button',
                                class: 'btn btn-default btn-xs all-comments'
                            })
                            .append('Показать все комментарии')
                            .on('click', function() {
                                $('.comment').removeClass('comment-permanent');
                                $('.comment').hide();
                                $('.comment-tooltip').remove();

                                if ($(this).hasClass('checked')) {
                                    $(this).removeClass('checked');
                                } else {
                                    $(this).addClass('checked');
                                }

                                if ($(this).hasClass('checked')) {
                                    TunnelEditor.ShowAllComments();
                                }
                            })
                        )
                    )
                    .append(
                        $('<div/>', {
                            class: 'btn-group pull-right',
                            role: 'group'
                        })
                        .css({
                            'margin-right': '15px'
                        })
                        .append(
                            $('<button/>', {
                                type: 'button',
                                class: 'btn btn-default btn-xs all-info'
                            })
                            .append('Показать доп информацию')
                            .on('click', function() {
                                $('.info-tooltip').hide();
                                $('.send_mail_info').show();
                                if ($(this).hasClass('checked')) {
                                    $(this).removeClass('checked');
                                } else {
                                    $(this).addClass('checked');
                                }

                                if ($(this).hasClass('checked')) {
                                    TunnelEditor.ShowAllInfo();
                                }
                            })
                        )
                    )
                },
                LoadTunnel: function() {
                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.Scheme,
                        data: {
                            tunnel_id: TunnelEditor.Tunnel
                        },
                        success: function(scheme) {
                            scheme.process.factors = $.evalJSON(scheme.process.factors);
                            scheme.process.style = $.evalJSON(scheme.process.style);
                            
                            TunnelEditor.Tunnel = scheme.process;
                            
                            $.each(scheme.actions, function() {
                                this.child_object = this.child_object ? this.child_object.split(':') : [],
                                this.settings = $.evalJSON(this.settings);
                                this.style = $.evalJSON(this.style);
                                
                                TunnelEditor.Objects.Actions[this.id] = this;
                                TunnelEditor.RenderObject('action', this.id);
                            });
                            
                            $.each(scheme.conditions, function() {
                                this.child_object_y = this.child_object_y ? this.child_object_y.split(':') : [],
                                this.child_object_n = this.child_object_n ? this.child_object_n.split(':') : [],
                                this.rules = $.evalJSON(this.rules);
                                this.style = $.evalJSON(this.style);
                                
                                TunnelEditor.Objects.Conditions[this.id] = this;
                                TunnelEditor.RenderObject('condition', this.id);
                            });
                            
                            $.each(scheme.timeouts, function() {
                                this.child_object = this.child_object ? this.child_object.split(':') : [],
                                this.style = $.evalJSON(this.style);
                                
                                TunnelEditor.Objects.Timeouts[this.id] = this;
                                TunnelEditor.RenderObject('timeout', this.id);
                            });
                            
                            TunnelEditor.RenderConnectors();
                            
                            $('#svg').height(scheme.process.style.height);
                            $('#svg, #objects').fadeIn(1000);
                            
                            $('#objects')
                            .on({
                                mousemove: function (e) {
                                    if (TunnelEditor.Connector) {
                                        var svg_offset = $('#svg').offset();

                                        var x = e.pageX - svg_offset.left;
                                        var y = e.pageY - svg_offset.top;

                                        $('#svg .tmp.connector').attr('x2', x);
                                        $('#svg .tmp.connector').attr('y2', y);
                                        
                                        $('#objects').css('cursor','crosshair');
                                    }
                                },
                                mouseup: function () {
                                    if (TunnelEditor.Connector) {
                                        TunnelEditor.Connector = false;
                                        
                                        $('#svg .tmp.connector').remove();
                                        $('.object > .connector').fadeOut(300);

                                        $('#objects').height(0);
                                        $('#objects').css('cursor','default');
                                    }
                                }
                            });

                            TunnelEditor.checkSvgHeight();
                        }
                    });
                },
                RenderObject: function(type, id) {
                    var object = $('<div/>', {
                        class: 'object',
                        'data-type': type + 's',
                        'data-id': id
                    });

                    switch (type) {
                        case 'action':
                            var action_icons = {
                                'send_mail': 'glyphicon-envelope',
                                'subscribe': 'glyphicon-check',
                                'random_subscribe': 'glyphicon-random',
                                'auto_complete': 'glyphicon-ok-circle',
                                'set_user_tag': 'glyphicon-tag',
                                'activate_process': 'glyphicon-play-circle',
                                'recycle_process': 'glyphicon-repeat',
                                'complete': 'glyphicon-off'
                            };

                            object
                            .addClass('action')
                            .css({
                                top: TunnelEditor.Objects.Actions[id].style.top,
                                left: TunnelEditor.Objects.Actions[id].style.left
                            })
                            .attr('data-comment', TunnelEditor.Objects.Actions[id].comment)
                            .append(
                                $('<div/>', {
                                    class: 'id'
                                })
                                .append(id)
                            )
                            .append(
                                $('<div/>', {
                                    class: 'users-count'
                                })
                                .append(
                                    $('<a/>', {
                                        href: TunnelEditor.Objects.Actions[id].url,
                                        target: '_blank'
                                    })
                                    .append(TunnelEditor.Objects.Actions[id].user_count ? TunnelEditor.Objects.Actions[id].user_count : 0)
                                )
                            )
                            .append(
                                $('<div/>', {
                                    class: 'connector parent'
                                })
                                .on('mousedown', function() {
                                    TunnelEditor.NewConnector('child', ['actions', id]);
                                })
                                .on('mouseup', function() {
                                    TunnelEditor.CreateConnector('child', ['actions', id]);
                                })
                            )
                            .append(
                                $('<div/>', {
                                    class: 'connector child'
                                })
                                .on('mousedown', function() {
                                    TunnelEditor.NewConnector('parent', ['actions', id]);
                                })
                                .on('mouseup', function() {
                                    TunnelEditor.CreateConnector('parent', ['actions', id]);
                                })
                            )
                            .append(
                                $('<div/>', {
                                    class: 'comment'
                                })
                                .append('<span>?</span>')
                                .on('mouseenter', function () {
                                    if (!$(this).hasClass('comment-permanent') && TunnelEditor.Objects.Actions[id].comment.length) {
                                        $(this).append(
                                            $('<div/>', {
                                                class: 'comment-tooltip'
                                            })
                                            .append(
                                                $('<span/>')
                                            )
                                        );
                                        var commentText = TunnelEditor.Objects.Actions[id].comment.replace(/\n/g,'<br>');
                                        $(this).find('.comment-tooltip span').html(commentText);
                                        $(this).find('.comment-tooltip').fadeIn(100);
                                        var commentTextWidth = $(this).find('.comment-tooltip span').width();
                                        $(this).find('.comment-tooltip').css('width', commentTextWidth + 10);
                                    }
                                })
                                .on('mouseleave', function () {
                                    if (!$(this).hasClass('comment-permanent')) {
                                        $(this).find('.comment-tooltip').fadeOut(100);
                                        $(this).find('.comment-tooltip').remove();
                                    }
                                })
                            )
                            .append(
                                $('<div/>', {
                                    class: 'action_icon glyphicon ' + action_icons[TunnelEditor.Objects.Actions[id].action]
                                })
                            )
                            .append(
                                $('<div/>', {
                                    class: 'action_details'
                                })
                            );

                            if (TunnelEditor.Objects.Actions[id].action == 'send_mail') {
                                object
                                .append(
                                    $('<div/>', {
                                        class: 'send_mail_info'
                                    })
                                    .html([
                                        'Отправлено писем: ' + TunnelEditor.Objects.Actions[id].letter_count,
                                        '% открытия: ' + Math.ceil(TunnelEditor.Objects.Actions[id].open_pct * 100)/100 + ' (' + TunnelEditor.Objects.Actions[id].open_count + ')',
                                        '% кликов: ' + Math.ceil(TunnelEditor.Objects.Actions[id].click_pct * 100)/100 + ' (' + TunnelEditor.Objects.Actions[id].click_count + ')',
                                        '% спама: ' + Math.ceil(TunnelEditor.Objects.Actions[id].spamreport_pct * 100)/100 + ' (' + TunnelEditor.Objects.Actions[id].spamreport_count + ')',
                                        '% отписок: ' + Math.ceil(TunnelEditor.Objects.Actions[id].unsubscribe_pct * 100)/100 + ' (' + TunnelEditor.Objects.Actions[id].unsubscribe_count + ')'
                                    ].join('<br>'))
                                )
                                .append(
                                    $('<div/>', {
                                        class: 'info-tooltip'
                                    })
                                    .html([
                                        'Отправлено писем: ' + TunnelEditor.Objects.Actions[id].letter_count,
                                        '% открытия: ' + Math.ceil(TunnelEditor.Objects.Actions[id].open_pct * 100)/100 + ' (' + TunnelEditor.Objects.Actions[id].open_count + ')',
                                        '% кликов: ' + Math.ceil(TunnelEditor.Objects.Actions[id].click_pct * 100)/100 + ' (' + TunnelEditor.Objects.Actions[id].click_count + ')',
                                        '% спама: ' + Math.ceil(TunnelEditor.Objects.Actions[id].spamreport_pct * 100)/100 + ' (' + TunnelEditor.Objects.Actions[id].spamreport_count + ')',
                                        '% отписок: ' + Math.ceil(TunnelEditor.Objects.Actions[id].unsubscribe_pct * 100)/100 + ' (' + TunnelEditor.Objects.Actions[id].unsubscribe_count + ')'
                                    ].join('<br>'))
                                )
                            }

                            TunnelEditor.updateActionDetails(id, object);

                            break;
                        case 'condition':
                            object
                            .addClass('condition')
                            .css({
                                top: TunnelEditor.Objects.Conditions[id].style.top,
                                left: TunnelEditor.Objects.Conditions[id].style.left
                            })
                            .attr('data-comment', TunnelEditor.Objects.Conditions[id].comment)
                            .append(
                                $('<div/>', {
                                    class: 'id'
                                })
                                .append(id)
                            )
                            .append(
                                $('<div/>', {
                                    class: 'users-count'
                                })
                                .append(
                                    $('<a/>', {
                                        href: TunnelEditor.Objects.Conditions[id].url,
                                        target: '_blank'
                                    })
                                    .append(TunnelEditor.Objects.Conditions[id].user_count ? TunnelEditor.Objects.Conditions[id].user_count : 0)
                                )
                            )
                            .append(
                                $('<div/>', {
                                    class: 'connector parent'
                                })
                                .on('mousedown', function() {
                                    TunnelEditor.NewConnector('child', ['conditions', id]);
                                })
                                .on('mouseup', function() {
                                    TunnelEditor.CreateConnector('child', ['conditions', id]);
                                })
                            )
                            .append(
                                $('<div/>', {
                                    class: 'connector child y'
                                })
                                .on('mousedown', function() {
                                    TunnelEditor.NewConnector('parent', ['conditions', id, 'y']);
                                })
                                .on('mouseup', function() {
                                    TunnelEditor.CreateConnector('parent', ['conditions', id, 'y']);
                                })
                            )
                            .append(
                                $('<div/>', {
                                    class: 'connector child n'
                                })
                                .on('mousedown', function() {
                                    TunnelEditor.NewConnector('parent', ['conditions', id, 'n']);
                                })
                                .on('mouseup', function() {
                                    TunnelEditor.CreateConnector('parent', ['conditions', id, 'n']);
                                })
                            )
                            .append(
                                $('<div/>', {
                                    class: 'comment'
                                })
                                .append('<span>?</span>')
                                .on('mouseenter', function () {
                                    if (!$(this).hasClass('comment-permanent') && TunnelEditor.Objects.Conditions[id].comment.length) {
                                        $(this).append(
                                            $('<div/>', {
                                                class: 'comment-tooltip'
                                            })
                                            .append(
                                                $('<span/>')
                                            )
                                        );
                                        var commentText = TunnelEditor.Objects.Conditions[id].comment.replace(/\n/g,'<br>');
                                        $(this).find('.comment-tooltip span').html(commentText);
                                        $(this).find('.comment-tooltip').fadeIn(100);
                                        var commentTextWidth = $(this).find('.comment-tooltip span').width();
                                        $(this).find('.comment-tooltip').css('width', commentTextWidth + 10);
                                    }
                                })
                                .on('mouseleave', function () {
                                    if (!$(this).hasClass('comment-permanent')) {
                                        $(this).find('.comment-tooltip').fadeOut(100);
                                        $(this).find('.comment-tooltip').remove();
                                    }
                                })
                            )
                            .append(
                                $('<div/>', {
                                    class: 'condition_title'
                                })
                                .append('Y - ? - N')
                            )
                            .append(
                                $('<div/>', {
                                    class: 'condition_details'
                                })
                            )

                            TunnelEditor.updateConditionDetails(id, object);

                            break;
                        case 'timeout':
                            var timeout_type_name = '';
                            
                            switch(TunnelEditor.Objects.Timeouts[id].timeout_type) {
                                case 'min': timeout_type_name = 'мин.'; break;
                                case 'hours': timeout_type_name = 'час.'; break;
                                case 'days': timeout_type_name = 'дн.'; break;
                            }
                            
                            object
                            .addClass('timeout')
                            .css({
                                top: TunnelEditor.Objects.Timeouts[id].style.top,
                                left: TunnelEditor.Objects.Timeouts[id].style.left
                            })
                            .attr('data-comment', TunnelEditor.Objects.Timeouts[id].comment)
                            .append(
                                $('<div/>', {
                                    class: 'id'
                                })
                                .append(id)
                            )
                            .append(
                                $('<div/>', {
                                    class: 'users-count'
                                })
                                .append(
                                    $('<a/>', {
                                        href: TunnelEditor.Objects.Timeouts[id].url,
                                        target: '_blank'
                                    })
                                    .append(TunnelEditor.Objects.Timeouts[id].user_count ? TunnelEditor.Objects.Timeouts[id].user_count : 0)
                                )
                            )
                            .append(
                                $('<div/>', {
                                    class: 'connector parent'
                                })
                                .on('mousedown', function() {
                                    TunnelEditor.NewConnector('child', ['timeouts', id]);
                                })
                                .on('mouseup', function() {
                                    TunnelEditor.CreateConnector('child', ['timeouts', id]);
                                })
                            )
                            .append(
                                $('<div/>', {
                                    class: 'connector child'
                                })
                                .on('mousedown', function() {
                                    TunnelEditor.NewConnector('parent', ['timeouts', id]);
                                })
                                .on('mouseup', function() {
                                    TunnelEditor.CreateConnector('parent', ['timeouts', id]);
                                })
                            )
                            .append(
                                $('<span/>', {
                                    class: 'glyphicon glyphicon-time'
                                })
                            )
                            .append(
                                $('<span/>', {
                                    class: 'value'
                                })
                                .append(TunnelEditor.Objects.Timeouts[id].timeout)
                            )
                            .append(
                                $('<div/>', {
                                    class: 'comment'
                                })
                                .append('<span>?</span>')
                                .on('mouseenter', function () {
                                    if (!$(this).hasClass('comment-permanent') && TunnelEditor.Objects.Timeouts[id].comment.length) {
                                        $(this).append(
                                            $('<div/>', {
                                                class: 'comment-tooltip'
                                            })
                                            .append(
                                                $('<span/>')
                                            )
                                        );
                                        var commentText = TunnelEditor.Objects.Timeouts[id].comment.replace(/\n/g,'<br>');
                                        $(this).find('.comment-tooltip span').html(commentText);
                                        $(this).find('.comment-tooltip').fadeIn(100);
                                        var commentTextWidth = $(this).find('.comment-tooltip span').width();
                                        $(this).find('.comment-tooltip').css('width', commentTextWidth + 10);
                                    }
                                })
                                .on('mouseleave', function () {
                                    if (!$(this).hasClass('comment-permanent')) {
                                        $(this).find('.comment-tooltip').fadeOut(100);
                                        $(this).find('.comment-tooltip').remove();
                                    }
                                })
                            )
                            .append(
                                $('<span/>', {
                                    class: 'type'
                                })
                                .append(timeout_type_name)
                            );
                            break;
                    }
                    

                    object
                    .draggable({
                        containment: '#objects',
                        grid: [10,10],
                        drag: function(event, ui) {
                            if (TunnelEditor.Connector) {
                                return false;
                            }
                            
                            switch (type) {
                                case 'action':
                                    TunnelEditor.Objects.Actions[id].style.top = ui.position.top;
                                    TunnelEditor.Objects.Actions[id].style.left = ui.position.left;
                                    break;
                                case 'condition':
                                    TunnelEditor.Objects.Conditions[id].style.top = ui.position.top;
                                    TunnelEditor.Objects.Conditions[id].style.left = ui.position.left;
                                    break;
                                case 'timeout':
                                    TunnelEditor.Objects.Timeouts[id].style.top = ui.position.top;
                                    TunnelEditor.Objects.Timeouts[id].style.left = ui.position.left;
                                    break;
                            }
                            
                            TunnelEditor.RenderConnectors();
                        },
                        stop: function(event, ui) {
                            switch (type) {
                                case 'action':
                                    TunnelEditor.UpdateAction(id);
                                    break;
                                case 'condition':
                                    TunnelEditor.UpdateCondition(id);
                                    break;
                                case 'timeout':
                                    TunnelEditor.UpdateTimeout(id);
                                    break;
                            }
                            TunnelEditor.checkSvgHeight();
                        }
                    })
                    .on('mouseenter', function() {
                        var details = $(this).attr('data-details');
                            classes = 'object-details-tooltip';

                        if (!TunnelEditor.Connector) {
                            $('.connector', this).fadeIn(100);
                            $('.id', this).fadeIn(100);
                            $('#objects').height(TunnelEditor.Tunnel.style.height);
                        }
                        if ($(this).find('.comment-permanent').length == 0) {
                            $('.comment', this).fadeIn(100);
                        }
                    })
                    .on('mouseleave', function() {
                        $('.object-details-tooltip', this).fadeOut(100);
                        if (!TunnelEditor.Connector) {
                            $('.connector', this).fadeOut(100);
                            $('.id', this).fadeOut(100);
                            $('#objects').height(0);
                        }
                        if ($(this).find('.comment-permanent').length == 0) {
                            $('.comment', this).fadeOut(100);
                        }
                    })
                    .on('dblclick', function() {
                        var form = $('<form/>', {
                            id: 'object-form',
                            class: 'form-horizontal',
                            tabindex: '-1',
                            role: 'dialog'
                        });
                        
                        switch (type) {
                            case 'action':
                                switch (TunnelEditor.Objects.Actions[id].action) {
                                    case 'send_mail':
                                        form
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_letter',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Письмо')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'hidden',
                                                        class: 'form-control',
                                                        id: 'in_letter'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.letter)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Комментарий')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<textarea/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        rows: 5,
                                                        id: 'in_comment'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].comment)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Логика')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<div/>', {
                                                        class: 'object-text-description'
                                                    })
                                                    .html(TunnelEditor.Objects.Actions[id].textDescription)
                                                )
                                            )
                                        )
                                        .on('submit', function(e) {
                                            if ($('#in_letter').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.letter = parseInt($('#in_letter').val());
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.letter;
                                            }
                                            
                                            if ($('#in_comment').val()) {
                                                TunnelEditor.Objects.Actions[id].comment = $('#in_comment').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].comment;
                                            }

                                            TunnelEditor.UpdateAction(id);
                                            e.preventDefault();
                                        });
                                        break;
                                    case 'subscribe':
                                        form
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_process_0',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Туннель')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'hidden',
                                                        class: 'form-control',
                                                        id: 'in_process_0'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.tunnel_id)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_process_1',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Тип объекта')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<select/>', {
                                                        class: 'form-control',
                                                        id: 'in_process_1'
                                                    })
                                                    .append(
                                                        $('<option/>', {
                                                            value: 'actions'
                                                        })
                                                        .append('Действие')
                                                    )
                                                    .append(
                                                        $('<option/>', {
                                                            value: 'conditions'
                                                        })
                                                        .append('Условие')
                                                    )
                                                    .append(
                                                        $('<option/>', {
                                                            value: 'timeouts'
                                                        })
                                                        .append('Таймер')
                                                    )
                                                    .val(TunnelEditor.Objects.Actions[id].settings.process_obj_type)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_process_2',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Объект')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        id: 'in_process_2'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.process_obj_id)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_activation_letter',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Письмо активации')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'hidden',
                                                        class: 'form-control',
                                                        id: 'in_activation_letter'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.activation_letter)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'activation_url',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('URL активации')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        id: 'in_activation_url'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.activation_url)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_welcome',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Индоктринация')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<select/>', {
                                                        class: 'form-control',
                                                        id: 'in_welcome'
                                                    })
                                                    .append(
                                                        $('<option/>', {
                                                            value: '0'
                                                        })
                                                        .append('Нет')
                                                    )
                                                    .append(
                                                        $('<option/>', {
                                                            value: '1'
                                                        })
                                                        .append('Да')
                                                    )
                                                    .val(TunnelEditor.Objects.Actions[id].settings.welcome)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_permanent_pause_processes',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .css({
                                                    'padding-top': '0'
                                                })
                                                .append('Постоянная пауза')
                                                .append(
                                                    $('<button/>', {
                                                        type: 'button',
                                                        class: 'btn btn-xs btn-default',
                                                        'data-toggle': 'popover',
                                                        title: 'Перевод туннелей на постоянную паузу',
                                                        'data-content': 'Перечислите ID туннелей через запятую, которые необходимо перевести в состояние [постоянная пауза] при успешной подписке на туннель.'
                                                    })
                                                    .css({
                                                        'margin-left': '5px'
                                                    })
                                                    .append(
                                                        $('<span/>', {
                                                            class: 'glyphicon glyphicon-question-sign',
                                                            'aria-hidden': 'true'
                                                        })
                                                    )
                                                    .popover()
                                                )
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        id: 'in_permanent_pause_processes'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.permanent_pause_processes)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Комментарий')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<textarea/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        rows: 5,
                                                        id: 'in_comment'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].comment)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Логика')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<div/>', {
                                                        class: 'object-text-description'
                                                    })
                                                    .html(TunnelEditor.Objects.Actions[id].textDescription)
                                                )
                                            )
                                        )
                                        .on('submit', function(e) {
                                            if ($('#in_process_0').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.tunnel_id = parseInt($('#in_process_0').val());
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.tunnel_id;
                                            }
                                            
                                            if ($('#in_process_1').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.process_obj_type = $('#in_process_1').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.process_obj_type;
                                            }
                                            
                                            if ($('#in_process_2').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.process_obj_id = parseInt($('#in_process_2').val());
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.process_obj_id;
                                            }
                                            
                                            TunnelEditor.Objects.Actions[id].settings.process_timeout = 0;
                                            
                                            if ($('#in_activation_letter').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.activation_letter = $('#in_activation_letter').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.activation_letter;
                                            }
                                            
                                            if ($('#in_activation_url').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.activation_url = $('#in_activation_url').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.activation_url;
                                            }
                                            
                                            if ($('#in_welcome').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.welcome = $('#in_welcome').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.welcome;
                                            }
                                            
                                            if ($('#in_permanent_pause_processes').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.permanent_pause_processes = $('#in_permanent_pause_processes').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.permanent_pause_processes;
                                            }

                                            if ($('#in_comment').val()) {
                                                TunnelEditor.Objects.Actions[id].comment = $('#in_comment').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].comment;
                                            }

                                            TunnelEditor.UpdateAction(id);
                                            e.preventDefault();
                                        });
                                        break;
                                    case 'random_subscribe':
                                        var random_subscribe_count = TunnelEditor.Objects.Actions[id].settings.processes.length;
                                        
                                        form
                                        .append(
                                            $('<div/>', {
                                                class: 'panel-group',
                                                id: 'object-processes-list',
                                                role: 'tablist'
                                            })
                                            .css({
                                                'margin': '0'
                                            })
                                        )
                                        .append(
                                            $('<button/>', {
                                                type: 'button',
                                                class: 'btn btn-default btn-block',
                                            })
                                            .css({
                                                'margin-bottom': '20px'
                                            })
                                            .append('Добавить туннель')
                                            .on('click', function() {
                                                $('#object-processes-list').append([
                                                    '<div class="panel panel-default" style="margin: 0 0 10px 0;">',
                                                        '<div class="panel-heading" role="tab">',
                                                            '<h4 class="panel-title" id="object-processes-list-title-' + random_subscribe_count + '">',
                                                                '<a data-parent="#object-processes-list" data-toggle="collapse" href="#object-processes-list-settings-' + random_subscribe_count + '">Туннель</a>',
                                                            '</h4>',
                                                        '</div>',
                                                        '<div class="panel-collapse collapse" id="object-processes-list-settings-' + random_subscribe_count + '" role="tabpanel">',
                                                            '<div class="panel-body"></div>',
                                                        '</div>',
                                                    '</div>',
                                                ].join(''));

                                                $('#object-processes-list-title-' + random_subscribe_count)
                                                .append(
                                                    $('<button/>', {
                                                        type: 'button',
                                                        class: 'btn btn-danger btn-xs pull-right'
                                                    })
                                                    .css({
                                                        'position': 'relative',
                                                        'top': '-2px'
                                                    })
                                                    .append('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>')
                                                    .on('click', function() {
                                                        $(this).closest('.panel').remove();
                                                    })
                                                );

                                                $('#object-processes-list-settings-' + random_subscribe_count + ' > .panel-body')
                                                .append(
                                                    $('<div/>', {
                                                        class: 'form-group'
                                                    })
                                                    .append(
                                                        $('<label/>', {
                                                            for: 'in_process_0_' + random_subscribe_count,
                                                            class: 'col-sm-2 control-label'
                                                        })
                                                        .append('Туннель')
                                                    )
                                                    .append(
                                                        $('<div/>', {
                                                            class: 'col-sm-10'
                                                        })
                                                        .append(
                                                            $('<input/>', {
                                                                type: 'hidden',
                                                                class: 'form-control',
                                                                id: 'in_process_0_' + random_subscribe_count
                                                            })
                                                        )
                                                    )
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'form-group'
                                                    })
                                                    .append(
                                                        $('<label/>', {
                                                            for: 'in_process_1_' + random_subscribe_count,
                                                            class: 'col-sm-2 control-label'
                                                        })
                                                        .append('Тип объекта')
                                                    )
                                                    .append(
                                                        $('<div/>', {
                                                            class: 'col-sm-10'
                                                        })
                                                        .append(
                                                            $('<select/>', {
                                                                class: 'form-control',
                                                                id: 'in_process_1_' + random_subscribe_count
                                                            })
                                                            .append(
                                                                $('<option/>', {
                                                                    value: 'actions'
                                                                })
                                                                .append('Действие')
                                                            )
                                                            .append(
                                                                $('<option/>', {
                                                                    value: 'conditions'
                                                                })
                                                                .append('Условие')
                                                            )
                                                            .append(
                                                                $('<option/>', {
                                                                    value: 'timeouts'
                                                                })
                                                                .append('Таймер')
                                                            )
                                                        )
                                                    )
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'form-group'
                                                    })
                                                    .append(
                                                        $('<label/>', {
                                                            for: 'in_process_2_' + random_subscribe_count,
                                                            class: 'col-sm-2 control-label'
                                                        })
                                                        .append('Объект')
                                                    )
                                                    .append(
                                                        $('<div/>', {
                                                            class: 'col-sm-10'
                                                        })
                                                        .append(
                                                            $('<input/>', {
                                                                type: 'text',
                                                                class: 'form-control',
                                                                id: 'in_process_2_' + random_subscribe_count
                                                            })
                                                        )
                                                    )
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'form-group'
                                                    })
                                                    .append(
                                                        $('<label/>', {
                                                            for: 'in_activation_letter_' + random_subscribe_count,
                                                            class: 'col-sm-2 control-label'
                                                        })
                                                        .append('Письмо активации')
                                                    )
                                                    .append(
                                                        $('<div/>', {
                                                            class: 'col-sm-10'
                                                        })
                                                        .append(
                                                            $('<input/>', {
                                                                type: 'hidden',
                                                                class: 'form-control',
                                                                id: 'in_activation_letter_' + random_subscribe_count
                                                            })
                                                        )
                                                    )
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'form-group'
                                                    })
                                                    .append(
                                                        $('<label/>', {
                                                            for: 'activation_url_' + random_subscribe_count,
                                                            class: 'col-sm-2 control-label'
                                                        })
                                                        .append('URL активации')
                                                    )
                                                    .append(
                                                        $('<div/>', {
                                                            class: 'col-sm-10'
                                                        })
                                                        .append(
                                                            $('<input/>', {
                                                                type: 'text',
                                                                class: 'form-control',
                                                                id: 'in_activation_url_' + random_subscribe_count
                                                            })
                                                        )
                                                    )
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'form-group'
                                                    })
                                                    .append(
                                                        $('<label/>', {
                                                            for: 'in_welcome_' + random_subscribe_count,
                                                            class: 'col-sm-2 control-label'
                                                        })
                                                        .append('Индоктринация')
                                                    )
                                                    .append(
                                                        $('<div/>', {
                                                            class: 'col-sm-10'
                                                        })
                                                        .append(
                                                            $('<select/>', {
                                                                class: 'form-control',
                                                                id: 'in_welcome_' + random_subscribe_count
                                                            })
                                                            .append(
                                                                $('<option/>', {
                                                                    value: '0'
                                                                })
                                                                .append('Нет')
                                                            )
                                                            .append(
                                                                $('<option/>', {
                                                                    value: '1'
                                                                })
                                                                .append('Да')
                                                            )
                                                        )
                                                    )
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'form-group'
                                                    })
                                                    .append(
                                                        $('<label/>', {
                                                            for: 'in_permanent_pause_processes_' + random_subscribe_count,
                                                            class: 'col-sm-2 control-label'
                                                        })
                                                        .css({
                                                            'padding-top': '0'
                                                        })
                                                        .append('Постоянная пауза')
                                                        .append(
                                                            $('<button/>', {
                                                                type: 'button',
                                                                class: 'btn btn-xs btn-default',
                                                                'data-toggle': 'popover',
                                                                title: 'Перевод туннелей на постоянную паузу',
                                                                'data-content': 'Перечислите ID туннелей через запятую, которые необходимо перевести в состояние [постоянная пауза] при успешной подписке на туннель.'
                                                            })
                                                            .css({
                                                                'margin-left': '5px'
                                                            })
                                                            .append(
                                                                $('<span/>', {
                                                                    class: 'glyphicon glyphicon-question-sign',
                                                                    'aria-hidden': 'true'
                                                                })
                                                            )
                                                            .popover()
                                                        )
                                                    )
                                                    .append(
                                                        $('<div/>', {
                                                            class: 'col-sm-10'
                                                        })
                                                        .append(
                                                            $('<input/>', {
                                                                type: 'text',
                                                                class: 'form-control',
                                                                id: 'in_permanent_pause_processes_' + random_subscribe_count
                                                            })
                                                        )
                                                    )
                                                );
                                                                                       
                                                $('#in_activation_letter_' + random_subscribe_count).MailingLetterSelector();
                                        
                                                ++ random_subscribe_count;
                                            })
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Комментарий')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<textarea/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        rows: 5,
                                                        id: 'in_comment'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].comment)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Логика')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<div/>', {
                                                        class: 'object-text-description'
                                                    })
                                                    .html(TunnelEditor.Objects.Actions[id].textDescription)
                                                )
                                            )
                                        )
                                        .on('submit', function(e) {
                                            var process_object = new Array();
                                            
                                            for (var i = 0; i <= random_subscribe_count; i++) {
                                                if ($('#object-processes-list-settings-' + i).length) {
                                                    process_object.push({
                                                        'tunnel_id': $('#in_process_0_' + i).val(),
                                                        'process_obj_type': $('#in_process_1_' + i).val(),
                                                        'process_obj_id': $('#in_process_2_' + i).val(),
                                                        'process_timeout': 0,
                                                        'activation_letter': $('#in_activation_letter_' + i).val(),
                                                        'activation_url': $('#in_activation_url_' + i).val(),
                                                        'welcome': $('#in_welcome_' + i).val(),
                                                        'permanent_pause_processes': $('#in_permanent_pause_processes_' + i).val()
                                                    });
                                                }
                                            }
                                            
                                            TunnelEditor.Objects.Actions[id].settings.processes = process_object;
                                   
                                            if ($('#in_comment').val()) {
                                                TunnelEditor.Objects.Actions[id].comment = $('#in_comment').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].comment;
                                            }

                                            TunnelEditor.UpdateAction(id);
                                            e.preventDefault();
                                        });
                                        break;
                                    case 'complete':
                                        form
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Комментарий')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<textarea/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        rows: 5,
                                                        id: 'in_comment'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].comment)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Логика')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<div/>', {
                                                        class: 'object-text-description'
                                                    })
                                                    .html(TunnelEditor.Objects.Actions[id].textDescription)
                                                )
                                            )
                                        )
                                        .on('submit', function(e) {
                                            if ($('#in_comment').val()) {
                                                TunnelEditor.Objects.Actions[id].comment = $('#in_comment').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].comment;
                                            }

                                            TunnelEditor.UpdateAction(id);
                                            e.preventDefault();
                                        });
                                        break;
                                    case 'auto_complete':
                                        form
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'tunnel_id',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Туннель')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        id: 'tunnel_id'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.tunnel_id)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Комментарий')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<textarea/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        rows: 5,
                                                        id: 'in_comment'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].comment)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Логика')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<div/>', {
                                                        class: 'object-text-description'
                                                    })
                                                    .html(TunnelEditor.Objects.Actions[id].textDescription)
                                                )
                                            )
                                        )
                                        .on('submit', function(e) {
                                            if ($('#tunnel_id').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.tunnel_id = $('#tunnel_id').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.tunnel_id;
                                            }
                                            if ($('#in_comment').val()) {
                                                TunnelEditor.Objects.Actions[id].comment = $('#in_comment').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].comment;
                                            }

                                            TunnelEditor.UpdateAction(id);
                                            e.preventDefault();
                                        });
                                        break;
                                    case 'set_user_tag':
                                        form
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'tag_id',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('ID тега')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        id: 'tag_id'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.tag_id)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'tag_details',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Детали тега')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        id: 'tag_details'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.tag_details)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Комментарий')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<textarea/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        rows: 5,
                                                        id: 'in_comment'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].comment)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Логика')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<div/>', {
                                                        class: 'object-text-description'
                                                    })
                                                    .html(TunnelEditor.Objects.Actions[id].textDescription)
                                                )
                                            )
                                        )
                                        .on('submit', function(e) {
                                            if ($('#tag_id').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.tag_id = $('#tag_id').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.tag_id;
                                            }
                                            if ($('#tag_details').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.tag_details = $('#tag_details').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.tag_details;
                                            }
                                            if ($('#in_comment').val()) {
                                                TunnelEditor.Objects.Actions[id].comment = $('#in_comment').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].comment;
                                            }

                                            TunnelEditor.UpdateAction(id);
                                            e.preventDefault();
                                        });
                                        break;
                                    case 'recycle_process':
                                        form
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Комментарий')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<textarea/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        rows: 5,
                                                        id: 'in_comment'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].comment)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Логика')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<div/>', {
                                                        class: 'object-text-description'
                                                    })
                                                    .html(TunnelEditor.Objects.Actions[id].textDescription)
                                                )
                                            )
                                        )
                                        .on('submit', function(e) {
                                            if ($('#in_comment').val()) {
                                                TunnelEditor.Objects.Actions[id].comment = $('#in_comment').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].comment;
                                            }

                                            TunnelEditor.UpdateAction(id);
                                            e.preventDefault();
                                        });
                                        break;
                                    case 'activate_process':
                                        form
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'tunnel_id',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Туннель')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<input/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        id: 'tunnel_id'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].settings.tunnel_id)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Комментарий')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<textarea/>', {
                                                        type: 'text',
                                                        class: 'form-control',
                                                        rows: 5,
                                                        id: 'in_comment'
                                                    })
                                                    .val(TunnelEditor.Objects.Actions[id].comment)
                                                )
                                            )
                                        )
                                        .append(
                                            $('<div/>', {
                                                class: 'form-group'
                                            })
                                            .append(
                                                $('<label/>', {
                                                    for: 'in_comment',
                                                    class: 'col-sm-2 control-label'
                                                })
                                                .append('Логика')
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'col-sm-10'
                                                })
                                                .append(
                                                    $('<div/>', {
                                                        class: 'object-text-description'
                                                    })
                                                    .html(TunnelEditor.Objects.Actions[id].textDescription)
                                                )
                                            )
                                        )
                                        .on('submit', function(e) {
                                            if ($('#tunnel_id').val()) {
                                                TunnelEditor.Objects.Actions[id].settings.tunnel_id = $('#tunnel_id').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].settings.tunnel_id;
                                            }
                                            if ($('#in_comment').val()) {
                                                TunnelEditor.Objects.Actions[id].comment = $('#in_comment').val();
                                            } else {
                                                delete TunnelEditor.Objects.Actions[id].comment;
                                            }

                                            TunnelEditor.UpdateAction(id);
                                            e.preventDefault();
                                        });
                                        break;
                                }
                                break;
                            case 'condition':
                                form
                                .append(
                                    $('<div/>', {
                                        class: 'form-group'
                                    })
                                    .append(
                                        $('<div/>', {
                                            class: 'col-sm-12'
                                        })
                                        .append(
                                            $('<input/>', {
                                                type: 'hidden',
                                                class: 'form-control',
                                                id: 'in_rules'
                                            })
                                            .val($.toJSON(TunnelEditor.Objects.Conditions[id].rules))
                                        )
                                    )
                                )
                                .append(
                                    $('<div/>', {
                                        class: 'form-group'
                                    })
                                    .append(
                                        $('<label/>', {
                                            for: 'in_comment',
                                            class: 'col-sm-2 control-label'
                                        })
                                        .append('Комментарий')
                                    )
                                    .append(
                                        $('<div/>', {
                                            class: 'col-sm-10'
                                        })
                                        .append(
                                            $('<textarea/>', {
                                                type: 'text',
                                                class: 'form-control',
                                                rows: 5,
                                                id: 'in_comment'
                                            })
                                            .val(TunnelEditor.Objects.Conditions[id].comment)
                                        )
                                    )
                                )
                                .append(
                                    $('<div/>', {
                                        class: 'form-group'
                                    })
                                    .append(
                                        $('<label/>', {
                                            for: 'in_comment',
                                            class: 'col-sm-2 control-label'
                                        })
                                        .append('Логика')
                                    )
                                    .append(
                                        $('<div/>', {
                                            class: 'col-sm-10'
                                        })
                                        .append(
                                            $('<div/>', {
                                                class: 'object-text-description'
                                            })
                                            .html(TunnelEditor.Objects.Conditions[id].textDescription)
                                        )
                                    )
                                )
                                .on('submit', function(e) {
                                    TunnelEditor.Objects.Conditions[id].rules = $.evalJSON($('#in_rules').val());
                                    TunnelEditor.Objects.Conditions[id].comment = $('#in_comment').val();
                                    TunnelEditor.UpdateCondition(id);
                                    e.preventDefault();
                                });
                                break;
                            case 'timeout':
                                form
                                .append(
                                    $('<div/>', {
                                        class: 'form-group'
                                    })
                                    .append(
                                        $('<label/>', {
                                            for: 'in_timeout',
                                            class: 'col-sm-2 control-label'
                                        })
                                        .append('Таймаут')
                                    )
                                    .append(
                                        $('<div/>', {
                                            class: 'col-sm-2'
                                        })
                                        .append(
                                            $('<input/>', {
                                                type: 'text',
                                                class: 'form-control',
                                                id: 'in_timeout'
                                            })
                                            .val(TunnelEditor.Objects.Timeouts[id].timeout)
                                        )
                                    )
                                    .append(
                                        $('<div/>', {
                                            class: 'col-sm-2'
                                        })
                                        .append(
                                            $('<select/>', {
                                                class: 'form-control',
                                                id: 'in_timeout_type'
                                            })
                                            .append(
                                                $('<option/>', {
                                                    value: 'min'
                                                })
                                                .append('мин.')
                                            )
                                            .append(
                                                $('<option/>', {
                                                    value: 'hours'
                                                })
                                                .append('час.')
                                            )
                                            .append(
                                                $('<option/>', {
                                                    value: 'days'
                                                })
                                                .append('дн.')
                                            )
                                            .val(TunnelEditor.Objects.Timeouts[id].timeout_type)
                                        )
                                    )
                                )
                                .append(
                                    $('<div/>', {
                                        class: 'form-group'
                                    })
                                    .append(
                                        $('<label/>', {
                                            for: 'in_comment',
                                            class: 'col-sm-2 control-label'
                                        })
                                        .append('Комментарий')
                                    )
                                    .append(
                                        $('<div/>', {
                                            class: 'col-sm-10'
                                        })
                                        .append(
                                            $('<textarea/>', {
                                                type: 'text',
                                                class: 'form-control',
                                                rows: 5,
                                                id: 'in_comment'
                                            })
                                            .val(TunnelEditor.Objects.Timeouts[id].comment)
                                        )
                                    )
                                )
                                .on('submit', function(e) {
                                    var timeout_type_name = '';
                            
                                    switch($('#in_timeout_type').val()) {
                                        case 'min': timeout_type_name = 'мин.'; break;
                                        case 'hours': timeout_type_name = 'час.'; break;
                                        case 'days': timeout_type_name = 'дн.'; break;
                                    }
                                    
                                    $('.object[data-type="timeouts"][data-id="' + id + '"] > .value').html($('#in_timeout').val());
                                    $('.object[data-type="timeouts"][data-id="' + id + '"] > .type').html(timeout_type_name);
                                    
                                    TunnelEditor.Objects.Timeouts[id].timeout = $('#in_timeout').val();
                                    TunnelEditor.Objects.Timeouts[id].timeout_type = $('#in_timeout_type').val();
                                    TunnelEditor.Objects.Timeouts[id].comment = $('#in_comment').val();
                                    
                                    TunnelEditor.Objects.Timeouts[id].comment = $('#in_comment').val();
                                    
                                    TunnelEditor.UpdateTimeout(id);
                                    e.preventDefault();
                                });
                                break;
                        }
                        
                        $('body')
                        .append(
                            $('<div/>', {
                                class: 'modal fade bs-example-modal-lg',
                                tabindex: '-1',
                                role: 'dialog'
                            })
                            .append(
                                $('<div/>', {
                                    class: 'modal-dialog modal-lg'
                                })
                                .append(
                                    $('<div/>', {
                                        class: 'modal-content'
                                    })
                                    .append(
                                        $('<div/>', {
                                            class: 'modal-header'
                                        })
                                        .append(
                                            $('<button/>', {
                                                type: 'button',
                                                class: 'close',
                                                'data-dismiss': 'modal',
                                                'aria-label': 'Close'
                                            })
                                            .append(
                                                $('<span/>', {
                                                    'aria-hidden': 'true'
                                                })
                                                .append('&times;')
                                            )
                                        )
                                        .append(
                                            $('<h4/>', {
                                                class: 'modal-title'
                                            })
                                            .append('Настройки объекта')
                                        )
                                    )
                                    .append(
                                        $('<div/>', {
                                            class: 'modal-body'
                                        })
                                        .append(form)
                                    )
                                    .append(
                                        $('<div/>', {
                                            class: 'modal-footer'
                                        })
                                        .append(
                                            $('<button/>', {
                                                type: 'button',
                                                class: 'btn btn-danger',
                                                'data-dismiss': 'modal'
                                            })
                                            .append('Удалить')
                                            .on('click', function() {
                                                $('.object[data-type="' + type + 's"][data-id="' + id + '"]').remove();
                                                
                                                switch (type) {
                                                    case 'action':
                                                        delete TunnelEditor.Objects.Actions[id];
                                                        
                                                        $.ajax({
                                                            type: 'post',
                                                            url: TunnelEditor.API.Actions.Remove,
                                                            data: {
                                                                action_id: id
                                                            }
                                                        });
                                                        break;
                                                    case 'condition':
                                                        delete TunnelEditor.Objects.Conditions[id];
                                                        
                                                        $.ajax({
                                                            type: 'post',
                                                            url: TunnelEditor.API.Conditions.Remove,
                                                            data: {
                                                                condition_id: id
                                                            }
                                                        });
                                                        break;
                                                    case 'timeout':
                                                        delete TunnelEditor.Objects.Timeouts[id];
                                                        
                                                        $.ajax({
                                                            type: 'post',
                                                            url: TunnelEditor.API.Timeouts.Remove,
                                                            data: {
                                                                timeout_id: id
                                                            }
                                                        });
                                                        break;
                                                }
                                                
                                                $.each(TunnelEditor.Objects.Actions, function() {
                                                    if (this.child_object.length) {
                                                        if (this.child_object.join(':') === type + 's:' + id) {
                                                            TunnelEditor.Objects.Actions[this.id].child_object = [];
                                                            TunnelEditor.UpdateAction(this.id);
                                                        }
                                                    }
                                                });

                                                $.each(TunnelEditor.Objects.Conditions, function() {
                                                    if (this.child_object_y.length) {
                                                        if (this.child_object_y.join(':') === type + 's:' + id) {
                                                            TunnelEditor.Objects.Conditions[this.id].child_object_y = [];
                                                            TunnelEditor.UpdateCondition(this.id);
                                                        }
                                                    }

                                                    if (this.child_object_n.length) {
                                                        if (this.child_object_n.join(':') === type + 's:' + id) {
                                                            TunnelEditor.Objects.Conditions[this.id].child_object_n = [];
                                                            TunnelEditor.UpdateCondition(this.id);
                                                        }
                                                    }
                                                });

                                                $.each(TunnelEditor.Objects.Timeouts, function() {
                                                    if (this.child_object.length) {
                                                        if (this.child_object.join(':') === type + 's:' + id) {
                                                            TunnelEditor.Objects.Timeouts[this.id].child_object = [];
                                                            TunnelEditor.UpdateTimeout(this.id);
                                                        }
                                                    }
                                                });

                                                TunnelEditor.RenderConnectors();
                                            })
                                        )
                                        .append(
                                            $('<button/>', {
                                                type: 'button',
                                                class: 'btn btn-primary pull-left',
                                                'data-dismiss': 'modal'
                                            })
                                            .append('Сохранить изменения')
                                            .on('click', function() {
                                                $('#object-form').submit();
                                            })
                                        )
                                        .append(
                                            $('<button/>', {
                                                type: 'button',
                                                class: 'btn btn-default pull-left',
                                                'data-dismiss': 'modal'
                                            })
                                            .append('Закрыть')
                                        )
                                    )
                                )
                            )
                            .modal('show')
                            .on('hidden.bs.modal', function (e) {
                                $(this).remove();
                            })
                        );
                
                        switch (type) {
                            case 'action':
                                switch (TunnelEditor.Objects.Actions[id].action) {
                                    case 'send_mail':
                                        $('#in_letter').MailingLetterSelector({'url':'<?= APP::Module('Routing')->root ?>'});
                                        break;
                                    case 'auto_complete':
                                        $('#tunnel_id').TunnelSelector({'url':'<?= APP::Module('Routing')->root ?>'});
                                        break;
                                    case 'activate_process':
                                        $('#tunnel_id').TunnelSelector({'url':'<?= APP::Module('Routing')->root ?>'});
                                        break;
                                    case 'subscribe':
                                        $('#in_process_0').TunnelSelector({'url':'<?= APP::Module('Routing')->root ?>'});
                                        $('#in_activation_letter').MailingLetterSelector({'url':'<?= APP::Module('Routing')->root ?>'});
                                        break;
                                    case 'random_subscribe': 
                                        $.each(TunnelEditor.Objects.Actions[id].settings.processes, function(i, process) {
                                            $('#object-processes-list').append([
                                                '<div class="panel panel-default" style="margin: 0 0 10px 0;">',
                                                    '<div class="panel-heading" role="tab">',
                                                        '<h4 class="panel-title" id="object-processes-list-title-' + i + '">',
                                                            '<a data-parent="#object-processes-list" data-toggle="collapse" href="#object-processes-list-settings-' + i + '">Туннель</a>',
                                                        '</h4>',
                                                    '</div>',
                                                    '<div class="panel-collapse collapse" id="object-processes-list-settings-' + i + '" role="tabpanel">',
                                                        '<div class="panel-body"></div>',
                                                    '</div>',
                                                '</div>',
                                            ].join(''));
                                            
                                            $('#object-processes-list-title-' + i)
                                            .append(
                                                $('<button/>', {
                                                    type: 'button',
                                                    class: 'btn btn-danger btn-xs pull-right'
                                                })
                                                .css({
                                                    'position': 'relative',
                                                    'top': '-2px'
                                                })
                                                .append('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>')
                                                .on('click', function() {
                                                    $(this).closest('.panel').remove();
                                                })
                                            );

                                            $('#object-processes-list-settings-' + i + ' > .panel-body')
                                            .append(
                                                $('<div/>', {
                                                    class: 'form-group'
                                                })
                                                .append(
                                                    $('<label/>', {
                                                        for: 'in_process_0_' + i,
                                                        class: 'col-sm-2 control-label'
                                                    })
                                                    .append('Туннель')
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'col-sm-10'
                                                    })
                                                    .append(
                                                        $('<input/>', {
                                                            type: 'hidden',
                                                            class: 'form-control',
                                                            id: 'in_process_0_' + i
                                                        })
                                                        .val(process.tunnel_id)
                                                    )
                                                )
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'form-group'
                                                })
                                                .append(
                                                    $('<label/>', {
                                                        for: 'in_process_1_' + i,
                                                        class: 'col-sm-2 control-label'
                                                    })
                                                    .append('Тип объекта')
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'col-sm-10'
                                                    })
                                                    .append(
                                                        $('<select/>', {
                                                            class: 'form-control',
                                                            id: 'in_process_1_' + i
                                                        })
                                                        .append(
                                                            $('<option/>', {
                                                                value: 'actions'
                                                            })
                                                            .append('Действие')
                                                        )
                                                        .append(
                                                            $('<option/>', {
                                                                value: 'conditions'
                                                            })
                                                            .append('Условие')
                                                        )
                                                        .append(
                                                            $('<option/>', {
                                                                value: 'timeouts'
                                                            })
                                                            .append('Таймер')
                                                        )
                                                        .val(process.process_obj_type)
                                                    )
                                                )
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'form-group'
                                                })
                                                .append(
                                                    $('<label/>', {
                                                        for: 'in_process_2_' + i,
                                                        class: 'col-sm-2 control-label'
                                                    })
                                                    .append('Объект')
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'col-sm-10'
                                                    })
                                                    .append(
                                                        $('<input/>', {
                                                            type: 'text',
                                                            class: 'form-control',
                                                            id: 'in_process_2_' + i
                                                        })
                                                        .val(process.process_obj_id)
                                                    )
                                                )
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'form-group'
                                                })
                                                .append(
                                                    $('<label/>', {
                                                        for: 'in_activation_letter_' + i,
                                                        class: 'col-sm-2 control-label'
                                                    })
                                                    .append('Письмо активации')
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'col-sm-10'
                                                    })
                                                    .append(
                                                        $('<input/>', {
                                                            type: 'hidden',
                                                            class: 'form-control',
                                                            id: 'in_activation_letter_' + i
                                                        })
                                                        .val(process.activation_letter)
                                                    )
                                                )
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'form-group'
                                                })
                                                .append(
                                                    $('<label/>', {
                                                        for: 'activation_url_' + i,
                                                        class: 'col-sm-2 control-label'
                                                    })
                                                    .append('URL активации')
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'col-sm-10'
                                                    })
                                                    .append(
                                                        $('<input/>', {
                                                            type: 'text',
                                                            class: 'form-control',
                                                            id: 'in_activation_url_' + i
                                                        })
                                                        .val(process.activation_url)
                                                    )
                                                )
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'form-group'
                                                })
                                                .append(
                                                    $('<label/>', {
                                                        for: 'in_welcome_' + i,
                                                        class: 'col-sm-2 control-label'
                                                    })
                                                    .append('Индоктринация')
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'col-sm-10'
                                                    })
                                                    .append(
                                                        $('<select/>', {
                                                            class: 'form-control',
                                                            id: 'in_welcome_' + i
                                                        })
                                                        .append(
                                                            $('<option/>', {
                                                                value: '0'
                                                            })
                                                            .append('Нет')
                                                        )
                                                        .append(
                                                            $('<option/>', {
                                                                value: '1'
                                                            })
                                                            .append('Да')
                                                        )
                                                        .val(process.welcome)
                                                    )
                                                )
                                            )
                                            .append(
                                                $('<div/>', {
                                                    class: 'form-group'
                                                })
                                                .append(
                                                    $('<label/>', {
                                                        for: 'in_permanent_pause_processes_' + i,
                                                        class: 'col-sm-2 control-label'
                                                    })
                                                    .css({
                                                        'padding-top': '0'
                                                    })
                                                    .append('Постоянная пауза')
                                                    .append(
                                                        $('<button/>', {
                                                            type: 'button',
                                                            class: 'btn btn-xs btn-default',
                                                            'data-toggle': 'popover',
                                                            title: 'Перевод туннелей на постоянную паузу',
                                                            'data-content': 'Перечислите ID туннелей через запятую, которые необходимо перевести в состояние [постоянная пауза] при успешной подписке на туннель.'
                                                        })
                                                        .css({
                                                            'margin-left': '5px'
                                                        })
                                                        .append(
                                                            $('<span/>', {
                                                                class: 'glyphicon glyphicon-question-sign',
                                                                'aria-hidden': 'true'
                                                            })
                                                        )
                                                        .popover()
                                                    )
                                                )
                                                .append(
                                                    $('<div/>', {
                                                        class: 'col-sm-10'
                                                    })
                                                    .append(
                                                        $('<input/>', {
                                                            type: 'text',
                                                            class: 'form-control',
                                                            id: 'in_permanent_pause_processes_' + i
                                                        })
                                                        .val(process.permanent_pause_processes)
                                                    )
                                                )
                                            );
                                    
                                            $('#in_activation_letter_' + i).MailingLetterSelector();
                                        });
                                        break;
                                }
                                break;
                            case 'condition':
                                $('#in_rules').TriggerRulesEditor({'url':'<?= APP::Module('Routing')->root ?>'});
                                break;
                        }
                    });
                    
                    $('#objects').append(object);
                },
                AddAction: function(data) {
                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.Actions.Create,
                        data: {
                            action: {
                                tunnel_id: data.tunnel_id,
                                action: data.action,
                                settings: $.toJSON(data.settings),
                                child_object: data.child_object.join(':'),
                                style: $.toJSON(data.style),
                                comment: data.comment
                            }
                        },
                        success: function(api) {
                            data.id = api.action.id;
                            TunnelEditor.Objects.Actions[api.action.id] = data;
                            TunnelEditor.RenderObject('action', api.action.id);
                        }
                    });
                },
                AddCondition: function(data) {
                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.Conditions.Create,
                        data: {
                            condition: {
                                tunnel_id: data.tunnel_id,
                                rules: $.toJSON(data.rules),
                                child_object_y: data.child_object_y.join(':'),
                                child_object_n: data.child_object_n.join(':'),
                                style: $.toJSON(data.style),
                                comment: data.comment
                            }
                        },
                        success: function(api) {
                            data.id = api.condition.id;
                            TunnelEditor.Objects.Conditions[api.condition.id] = data;
                            TunnelEditor.RenderObject('condition', api.condition.id);
                        }
                    });
                },
                AddTimeout: function(data) {
                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.Timeouts.Create,
                        data: {
                            timeout: {
                                tunnel_id: data.tunnel_id,
                                timeout: data.timeout,
                                timeout_type: data.timeout_type,
                                child_object: data.child_object.join(':'),
                                style: $.toJSON(data.style),
                                comment: data.comment
                            }
                        },
                        success: function(api) {
                            data.id = api.timeout.id;
                            TunnelEditor.Objects.Timeouts[api.timeout.id] = data;
                            TunnelEditor.RenderObject('timeout', api.timeout.id);
                        }
                    });
                },
                UpdateAction: function(id) {
                    //update details
                    TunnelEditor.updateActionDetails(id, $('.object.action[data-id="' + id + '"]'));

                    //update comments
                    $('.object.action[data-id="' + id + '"]').attr('data-comment', TunnelEditor.Objects.Actions[id].comment);

                    if ($('button.all-comments').hasClass('checked')) {
                        $('.comment').removeClass('comment-permanent');
                        $('.comment').hide();
                        $('.comment-tooltip').remove();

                        TunnelEditor.ShowAllComments();
                    }

                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.Actions.Update,
                        data: {
                            action: {
                                id: id,
                                tunnel_id: TunnelEditor.Objects.Actions[id].tunnel_id,
                                action: TunnelEditor.Objects.Actions[id].action,
                                comment: TunnelEditor.Objects.Actions[id].comment,
                                settings: $.toJSON(TunnelEditor.Objects.Actions[id].settings),
                                child_object: TunnelEditor.Objects.Actions[id].child_object.join(':'),
                                style: $.toJSON(TunnelEditor.Objects.Actions[id].style)
                            }
                        },
                        success: function() {
                        }
                    });
                },
                UpdateCondition: function(id) {
                    //update details
                    TunnelEditor.updateConditionDetails(id, $('.object.action[data-id="' + id + '"]'));

                    //update comments
                    $('.object.condition[data-id="' + id + '"]').attr('data-comment', TunnelEditor.Objects.Conditions[id].comment);

                    if ($('button.all-comments').hasClass('checked')) {
                        $('.comment').removeClass('comment-permanent');
                        $('.comment').hide();
                        $('.comment-tooltip').remove();

                        TunnelEditor.ShowAllComments();
                    }

                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.Conditions.Update,
                        data: {
                            condition: {
                                id: id,
                                tunnel_id: TunnelEditor.Objects.Conditions[id].tunnel_id,
                                comment: TunnelEditor.Objects.Conditions[id].comment,
                                rules: $.toJSON(TunnelEditor.Objects.Conditions[id].rules),
                                child_object_y: TunnelEditor.Objects.Conditions[id].child_object_y.join(':'),
                                child_object_n: TunnelEditor.Objects.Conditions[id].child_object_n.join(':'),
                                style: $.toJSON(TunnelEditor.Objects.Conditions[id].style),
                                comment: TunnelEditor.Objects.Conditions[id].comment
                            }
                        },
                        success: function() {
                            
                        }
                    });
                },
                UpdateTimeout: function(id) {
                    $('.object.timeout[data-id="' + id + '"]').attr('data-comment', TunnelEditor.Objects.Timeouts[id].comment);

                    if ($('button.all-comments').hasClass('checked')) {
                        $('.comment').removeClass('comment-permanent');
                        $('.comment').hide();
                        $('.comment-tooltip').remove();

                        TunnelEditor.ShowAllComments();
                    }

                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.Timeouts.Update,
                        data: {
                            timeout: {
                                id: id,
                                tunnel_id: TunnelEditor.Objects.Timeouts[id].tunnel_id,
                                timeout: TunnelEditor.Objects.Timeouts[id].timeout,
                                timeout_type: TunnelEditor.Objects.Timeouts[id].timeout_type,
                                comment: TunnelEditor.Objects.Timeouts[id].comment,
                                child_object: TunnelEditor.Objects.Timeouts[id].child_object.join(':'),
                                style: $.toJSON(TunnelEditor.Objects.Timeouts[id].style),
                                comment: TunnelEditor.Objects.Timeouts[id].comment 
                            }
                        },
                        success: function() {
                            
                        }
                    });
                },
                UpdateScheme: function() {
                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.SchemeUpdate,
                        data: {
                            id: TunnelEditor.Tunnel.id,
                            type: TunnelEditor.Tunnel.type,
                            state: TunnelEditor.Tunnel.state,
                            name: TunnelEditor.Tunnel.name,
                            description: TunnelEditor.Tunnel.description,
                            factors: $.toJSON(TunnelEditor.Tunnel.factors),
                            style: $.toJSON(TunnelEditor.Tunnel.style)
                        },
                        success: function() {
                            
                        }
                    });
                },
                RenderConnectors: function() {
                    $('#svg > .connector').remove();

                    $.each(TunnelEditor.Objects.Actions, function() {
                        if (this.child_object && this.child_object.length) {
                            TunnelEditor.AddConnector(['actions', this.id], [this.child_object[0], this.child_object[1]]);
                        }
                    });

                    $.each(TunnelEditor.Objects.Conditions, function() {
                        if (this.child_object_y && this.child_object_y.length) {
                            TunnelEditor.AddConnector(['conditions', this.id, 'y'], [this.child_object_y[0], this.child_object_y[1]]);
                        }
                        
                        if (this.child_object_n && this.child_object_n.length) {
                            TunnelEditor.AddConnector(['conditions', this.id, 'n'], [this.child_object_n[0], this.child_object_n[1]]);
                        }
                    });

                    $.each(TunnelEditor.Objects.Timeouts, function() {
                        if (this.child_object.length) {
                            TunnelEditor.AddConnector(['timeouts', this.id], [this.child_object[0], this.child_object[1]]);
                        }
                    });
                },
                AddConnector: function(parent, child) {
                    var line = document.createElementNS('http://www.w3.org/2000/svg','line');
                    
                    var x1, x2, 
                        y1, y2 = 0;

                    switch(parent[0]) {
                        case 'actions': 
                            x1 = TunnelEditor.Objects.Actions[parent[1]].style.left + 100;
                            y1 = TunnelEditor.Objects.Actions[parent[1]].style.top + 100;
                            break;
                        case 'conditions': 
                            switch(parent[2]) {
                                case 'y': 
                                    x1 = TunnelEditor.Objects.Conditions[parent[1]].style.left;
                                    y1 = TunnelEditor.Objects.Conditions[parent[1]].style.top + 44;
                                    break;
                                case 'n': 
                                    x1 = TunnelEditor.Objects.Conditions[parent[1]].style.left + 260;
                                    y1 = TunnelEditor.Objects.Conditions[parent[1]].style.top + 44;
                                    break;
                            }
                            break;
                        case 'timeouts': 
                            x1 = TunnelEditor.Objects.Timeouts[parent[1]].style.left + 50;
                            y1 = TunnelEditor.Objects.Timeouts[parent[1]].style.top + 100;
                            break;
                    }
                    
                    switch(child[0]) {
                        case 'actions': 
                            x2 = TunnelEditor.Objects.Actions[child[1]].style.left + 100;
                            y2 = TunnelEditor.Objects.Actions[child[1]].style.top;
                            break;
                        case 'conditions': 
                            x2 = TunnelEditor.Objects.Conditions[child[1]].style.left + 130;
                            y2 = TunnelEditor.Objects.Conditions[child[1]].style.top;
                            break;
                        case 'timeouts': 
                            x2 = TunnelEditor.Objects.Timeouts[child[1]].style.left + 50;
                            y2 = TunnelEditor.Objects.Timeouts[child[1]].style.top;
                            break;
                    }
                    
                    line.setAttribute('class', 'connector');
                    line.setAttribute('x1', x1);
                    line.setAttribute('y1', y1);
                    line.setAttribute('x2', x2);
                    line.setAttribute('y2', y2);
                    
                    $(line).on('click', function (e) {
                        switch(parent[0]) {
                            case 'actions': 
                                TunnelEditor.Objects.Actions[parent[1]].child_object = [];
                                TunnelEditor.UpdateAction(parent[1]);
                                break;
                            case 'conditions': 
                                TunnelEditor.Objects.Conditions[parent[1]]['child_object_' + parent[2]] = [];
                                TunnelEditor.UpdateCondition(parent[1]);
                                break;
                            case 'timeouts': 
                                TunnelEditor.Objects.Timeouts[parent[1]].child_object = [];
                                TunnelEditor.UpdateTimeout(parent[1]);
                                break;
                        }
                        
                        TunnelEditor.RenderConnectors();
                    });

                    $('#svg').append(line);
                },
                NewConnector: function(from, object) {
                    TunnelEditor.Connector = {};
                    TunnelEditor.Connector[from] = object;
                    
                    $('.object > .connector.' + from).fadeIn(300);
                    
                    var line = document.createElementNS('http://www.w3.org/2000/svg','line');
                    
                    var x1, y1 = 0;

                    switch(from) {
                        case 'parent': 
                            switch(object[0]) {
                                case 'actions': 
                                    x1 = TunnelEditor.Objects.Actions[object[1]].style.left + 100;
                                    y1 = TunnelEditor.Objects.Actions[object[1]].style.top + 100;
                                    break;
                                case 'conditions': 
                                    switch(object[2]) {
                                        case 'y': 
                                            x1 = TunnelEditor.Objects.Conditions[object[1]].style.left;
                                            y1 = TunnelEditor.Objects.Conditions[object[1]].style.top + 44;
                                            break;
                                        case 'n': 
                                            x1 = TunnelEditor.Objects.Conditions[object[1]].style.left + 260;
                                            y1 = TunnelEditor.Objects.Conditions[object[1]].style.top + 44;
                                            break;
                                    }
                                    break;
                                case 'timeouts': 
                                    x1 = TunnelEditor.Objects.Timeouts[object[1]].style.left + 50;
                                    y1 = TunnelEditor.Objects.Timeouts[object[1]].style.top + 100;
                                    break;
                            }
                            break;
                         case 'child': 
                            switch(object[0]) {
                                case 'actions': 
                                    x1 = TunnelEditor.Objects.Actions[object[1]].style.left + 100;
                                    y1 = TunnelEditor.Objects.Actions[object[1]].style.top;
                                    break;
                                case 'conditions': 
                                    x1 = TunnelEditor.Objects.Conditions[object[1]].style.left + 130;
                                    y1 = TunnelEditor.Objects.Conditions[object[1]].style.top;
                                    break;
                                case 'timeouts': 
                                    x1 = TunnelEditor.Objects.Timeouts[object[1]].style.left + 50;
                                    y1 = TunnelEditor.Objects.Timeouts[object[1]].style.top;
                                    break;
                            }
                            break;
                    }

                    line.setAttribute('class', 'tmp connector');
                    line.setAttribute('x1', x1);
                    line.setAttribute('y1', y1);
                    line.setAttribute('x2', x1);
                    line.setAttribute('y2', y1);

                    $('#svg').append(line);
                },
                CreateConnector: function(to, object) {
                    TunnelEditor.Connector[to] = object;

                    switch(TunnelEditor.Connector.parent[0]) {
                        case 'actions':
                            TunnelEditor.Objects.Actions[TunnelEditor.Connector.parent[1]].child_object = TunnelEditor.Connector.child;
                            TunnelEditor.UpdateAction(TunnelEditor.Connector.parent[1]);
                            break;
                        case 'conditions':
                            TunnelEditor.Objects.Conditions[TunnelEditor.Connector.parent[1]]['child_object_' + TunnelEditor.Connector.parent[2]] = TunnelEditor.Connector.child;
                            TunnelEditor.UpdateCondition(TunnelEditor.Connector.parent[1]);
                            break;
                        case 'timeouts':
                            TunnelEditor.Objects.Timeouts[TunnelEditor.Connector.parent[1]].child_object = TunnelEditor.Connector.child;
                            TunnelEditor.UpdateTimeout(TunnelEditor.Connector.parent[1]);
                            break;
                    }
                    
                    TunnelEditor.RenderConnectors();
                },
                ShowAllComments: function () {
                    $('.object').each(function (i, item) {
                        var commentText = $(item).attr('data-comment').replace(/\n/g,'<br>');
                        if (commentText.length) {
                            $(item).append(
                                $(item).find('.comment')
                                .addClass('comment-permanent')
                                .append(
                                    $('<div/>', {
                                        class: 'comment-tooltip'
                                    })
                                    .append(
                                        $('<span/>')
                                    )
                                )
                            )
                            $(item).find('.comment').fadeIn(100);
                            $(item).find('.comment-tooltip span').html(commentText);
                            $('.comment-tooltip').fadeIn(100);
                            var commentTextWidth = $(item).find('.comment-tooltip span').width();
                            $(item).find('.comment-tooltip').css('width', commentTextWidth + 10);
                        }
                    })
                },
                ShowAllInfo: function () {
                    $('.info-tooltip').show();
                    $('.send_mail_info').hide();
                },
                updateActionDetails: function(id, object) {
                    var action = TunnelEditor.Objects.Actions[id],
                        action_type = action.action,
                        action_details = '';

                    switch (action_type) {
                        case 'send_mail':
                            if (action.settings.letter) {
                                TunnelEditor.getSendMailInfo(action.settings.letter, function (sendMailInfo) {
                                    action_details += 'Тема: '+ sendMailInfo.subject;
                                    if (sendMailInfo.sender) {
                                        action_details += '<br>Отправитель: ' + sendMailInfo.sender
                                    }
                                    object.find('.action_details').html(action_details);
                                    action.textDescription = action_details;
                                })
                            }
                            break;
                        case 'subscribe':
                            if (action.settings.tunnel_id) {
                                action_details = 'Подписка на туннель<br>"' + TunnelEditor.getTunnelNameById(action.settings.tunnel_id) + '"';
                                object.find('.action_details').html(action_details);
                                action.textDescription = action_details;
                            }
                            break;
                        case 'random_subscribe':
                            var description_details = '';
                            action_details = 'Случайная подписка<br>'
                            $.each(action.settings.processes, function (i, item) {
                                description_details += '"' + TunnelEditor.getTunnelNameById(item.tunnel_id) + '"<br>';
                                action_details += '"' + TunnelEditor.getTunnelNameById(item.tunnel_id, 20) + '"<br>';
                            });
                            object.find('.action_details').html(action_details);
                            action.textDescription = description_details;
                            break;
                        case 'auto_complete':
                            if (action.settings.tunnel_id) {
                                action_details = 'Автозавершение туннеля <br>"' + TunnelEditor.getTunnelNameById(action.settings.tunnel_id) + '"';
                                object.find('.action_details').html(action_details);
                                action.textDescription = action_details;
                            }
                            break;
                        case 'set_user_tag':
                            if (action.settings.tag_id) {
                                action_details = 'Установка/обновление метки пользователя <br>"' + action.settings.tag_id + ' = ' + action.settings.tag_details + '"';
                                object.find('.action_details').html(action_details);
                                action.textDescription = action_details;
                            }
                            break;
                        case 'activate_process':
                            if (action.settings.tunnel_id) {
                                action_details = 'Снятие с паузы туннеля<br>"' + TunnelEditor.getTunnelNameById(action.settings.tunnel_id) + '"';
                                object.find('.action_details').html(action_details);
                                action.textDescription = action_details;
                            }
                            break;
                        case 'recycle_process':
                            object.find('.action_details').html('Возврат в точку подписки');
                            action.textDescription = 'Возврат в точку подписки';
                            break;
                        case 'complete':
                            object.find('.action_details').html('Завершение туннеля');
                            action.textDescription = 'Завершение туннеля';
                            break;
                    }
                },
                getTunnelNameById: function (id, shortedTo) {
                    var data = <?php echo json_encode($data['tunnels']); ?>,
                        name = data[id] ? data[id].name : id;
                    
                    if (shortedTo && typeof(shortedTo) == 'number') {
                        name = name.slice(0, shortedTo) + '...';
                    }

                    return name;
                },
                updateConditionDetails: function (id, object) {
                    var condition = TunnelEditor.Objects.Conditions[id],
                        rules = condition.rules;

                    var getDescription = function (obj) {
                        var logic = obj.logic,
                            line = [],
                            description = '',
                            connector = '';

                        if (logic == 'intersect') {
                            connector = ' И ';
                        } else if (logic == 'merge') {
                            connector = ' ИЛИ ';
                        }

                        if (obj.rules) {
                            obj.rules.forEach(function (rule) {
                                var conditionItem = '';
                                switch (rule.method) {
                                    case 'user_not_processes':
                                        if (rule.settings.type == 'static') {
                                            conditionItem = 'не получает статический туннель'
                                        } else {
                                            conditionItem = 'не получает динамический туннель'
                                        }
                                        break;
                                    case 'exist_queue_processes':
                                        conditionItem = 'нет туннелей в очереди'
                                        break;
                                    case 'user_processes':
                                        if (rule.settings.type == 'exist') {
                                            conditionItem = 'есть подписка на "' + TunnelEditor.getTunnelNameById(rule.settings.value) + '"';
                                        }
                                        if (rule.settings.type == 'not_exist') {
                                            conditionItem = 'нет подписки на "' + TunnelEditor.getTunnelNameById(rule.settings.value) + '"';
                                        }
                                        if (rule.settings.type == 'active') {
                                            conditionItem = 'активная подписка на "' + TunnelEditor.getTunnelNameById(rule.settings.value) + '"';
                                        }
                                        if (rule.settings.type == 'complete') {
                                            conditionItem = 'пройденный туннель "' + TunnelEditor.getTunnelNameById(rule.settings.value) + '"';
                                        }
                                        if (rule.settings.type == 'pause') {
                                            conditionItem = 'на паузе туннель "' + TunnelEditor.getTunnelNameById(rule.settings.value) + '"';
                                        }
                                        if (rule.settings.type == 'permanent_pause') {
                                            conditionItem = 'на постоянной паузе туннель "' + TunnelEditor.getTunnelNameById(rule.settings.value) + '"';
                                        }
                                        break;
                                    case 'letter_click':
                                        conditionItem = 'есть клик в письме с ID ' + rule.settings.letter;
                                        break;
                                    case 'send_mail':
                                        conditionItem = 'отправлено письмо с ID ' + rule.settings.letter;
                                        break;
                                    case 'tunnel_label':
                                        conditionItem = 'есть метка ' + rule.settings.label_id + ' процесса с ID ' + rule.settings.tunnel_id;
                                        break;
                                    case 'letter_open':
                                        conditionItem = 'открытие письма с ID ' + rule.settings.letter;
                                        break;
                                    case 'user_product_availability':
                                        conditionItem = 'доступность продукта ' + rule.settings.value;
                                        break;
                                }
                                line.push('(' + conditionItem + ')');
                            })
                            description = line.join(connector);
                        }

                        if (obj.children) {
                            description += connector + '(' + getDescription(obj.children) + ')';
                        }

                        return description;
                    }

                    object.find('.condition_details').html(getDescription(rules));
                    condition.textDescription = getDescription(rules);
                },
                getSendMailInfo: function(letterId, cb) {
                    var sendMailInfo = {};
                    TunnelEditor.getLetterById(letterId, function (letterData) {
                        sendMailInfo.subject = letterData[0].subject;
                        if (letterData[0].sender) {
                            TunnelEditor.getSenderById(letterData[0].sender, function (senderData) {
                                sendMailInfo.sender = senderData[0].name;
                                cb(sendMailInfo);
                            })
                        } else {
                            cb(sendMailInfo);
                        }
                    })
                },
                getLetterById: function (id, cb) {
                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.GetLetters,
                        data: {
                            select: ['id', 'subject', 'sender'],
                            where: [
                                ['id', '=', id]
                            ]
                        },
                        success: function(data) {
                            cb(data);
                        }
                    });
                },
                getSenderById: function (id, cb) {
                    $.ajax({
                        type: 'post',
                        url: TunnelEditor.API.GetSenders,
                        data: {
                            select: ['name'],
                            where: [
                                ['id', '=', id]
                            ]
                        },
                        success: function(data) {
                            cb(data);
                        }
                    });
                },
                checkSvgHeight: function () {
                    var offsetTopMax = 0;
                    $('.object').each(function (i, item) {
                        if (item.offsetTop > offsetTopMax) {
                            offsetTopMax = item.offsetTop;
                        }
                    })

                    TunnelEditor.Tunnel.style.height = offsetTopMax + 300;
                    $('#svg').css('height', offsetTopMax + 300);

                    TunnelEditor.UpdateScheme();

                }
            };
            
            $('#open_process')
            .val(<?= APP::Module('Crypt')->Decode(APP::Module('Routing')->get['tunnel_id_hash']) ?>)
            /*
            .TunnelSelector({
                'button_icon': 'glyphicon glyphicon-folder-open',
                'default_button_text': 'открыть туннель',
                'tunnel_link': false,
                'button_size': 'btn-xs',
                callback: function(process) {
                    TunnelEditor.Init(process.id);
                }
            })*/;
            TunnelEditor.Init(<?= APP::Module('Crypt')->Decode(APP::Module('Routing')->get['tunnel_id_hash']) ?>);
        </script>
    </body>
</html>