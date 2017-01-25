/**
 * @author Selivanov Max <max@evildevel.com>
 * 
 * Dependencies:
 * jquery.json (https://github.com/krinkle/jquery-json)
 * 
 */

(function($) {
    var settings;
    var methods = {
        init: function(options) { 
            $target_rules = $(this);

            $actions = {
                'send_mail': 'Отправка письма',
                'tunnel_label': 'Метка туннеля',
            };

            settings = $.extend( {
                'rules' : $.evalJSON($(this).val()),
                'url'   : 'http://pult2.glamurnenko.ru/',
                'debug' : false
            }, options);

            $('<div/>', {
                'id': 'trigger_rules_editor',
            }).insertAfter(this);

            $('<div/>', {
                'class': 'trigger_children',
            }).appendTo($('#trigger_rules_editor'));

            methods.render_rules(settings.rules, $('.trigger_children'));

            return this;
        },
        render_value: function(trigger_rule) { // .trigger_rule
            var value = {
                'logic': $('> table > tbody > tr > .trigger_logic_holder > .trigger_logic > select', trigger_rule).val()
            };

            var trigger_rule_item = $('> table > tbody > tr > .trigger_holder > .trigger_rules > .trigger_rule_item', trigger_rule);
            var trigger_children_rule = $('> table > tbody > tr > .trigger_holder > .trigger_children > .trigger_rule', trigger_rule);

            if (trigger_rule_item.length) {
                value.rules = [];

                trigger_rule_item.each(function() {
                    var method = $(this).data('method');
                    var settings = new Object();

                    $('.trigger_settings input, .trigger_settings select', this).each(function() {
                        id = $(this).data('id');
                        param_value = $(this).val();

                        if (param_value) {
                            switch(method) {
                                case 'product_availability': 
                                    switch(id) {
                                        case 'letter': settings[id] = parseInt(param_value); break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'send_mail': 
                                    switch(id) {
                                        case 'letter': settings[id] = parseInt(param_value); break;
                                        case 'timeout-value': 
                                            if (settings.timeout === undefined) settings.timeout = new Object();
                                            settings.timeout.value = parseInt(param_value); 
                                            break;
                                        case 'timeout-mode': 
                                            if (settings.timeout === undefined) settings.timeout = new Object();
                                            settings.timeout.mode = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'tunnel_label': 
                                    switch(id) {
                                        case 'tunnel_id': settings[id] = parseInt(param_value); break;
                                        case 'timeout-value': 
                                            if (settings.timeout === undefined) settings.timeout = new Object();
                                            settings.timeout.value = parseInt(param_value); 
                                            break;
                                        case 'timeout-mode': 
                                            if (settings.timeout === undefined) settings.timeout = new Object();
                                            settings.timeout.mode = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'letter_click': 
                                    switch(id) {
                                        case 'letter': settings[id] = parseInt(param_value); break;
                                        case 'timeout-value': 
                                            if (settings.timeout === undefined) settings.timeout = new Object();
                                            settings.timeout.value = parseInt(param_value); 
                                            break;
                                        case 'timeout-mode': 
                                            if (settings.timeout === undefined) settings.timeout = new Object();
                                            settings.timeout.mode = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'letter_open': 
                                    switch(id) {
                                        case 'letter': settings[id] = parseInt(param_value); break;
                                        case 'timeout-value': 
                                            if (settings.timeout === undefined) settings.timeout = new Object();
                                            settings.timeout.value = parseInt(param_value); 
                                            break;
                                        case 'timeout-mode': 
                                            if (settings.timeout === undefined) settings.timeout = new Object();
                                            settings.timeout.mode = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                default: settings[id] = param_value;
                            }
                        }
                    });

                    value.rules.push({
                        'method': method,
                        'settings': settings
                    });
                });
            }

            if (trigger_children_rule.length) {
                value.children = methods.render_value(trigger_children_rule);
            }

            return value;
        },
        add_trigger_rule: function() {
            var trigger_rule = $(this).closest('.trigger_rule');
            var trigger_logic = $(this).data('logic');
            var trigger_method = $(this).data('method');

            if (trigger_logic === $('> table > tbody > tr > .trigger_logic_holder > .trigger_logic > select', trigger_rule).val()) {
                methods.render_rule({
                    'method': trigger_method,
                    'settings': new Object()
                }, $('> table > tbody > tr > .trigger_holder > .trigger_rules', trigger_rule));
            } else {
                var trigger_children = $(trigger_rule).closest('.trigger_children');

                trigger_rule.remove();

                methods.render_rules({
                    'logic': trigger_logic,
                    'rules': [{
                        method: trigger_method,
                        'settings': new Object()
                    }],
                    'children': methods.render_value(trigger_rule)
                }, trigger_children);
            }

            $target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))));
        },
        remove_rule_item: function() {
            $(this).closest('.trigger_rule_item').remove();
            $target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))));
        },
        remove_trigger_rule: function() {
            $(this).closest('.trigger_rule').remove();
            $target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))));
        },
        render_rules: function(rules, holder) {
            $trigger_rule = $('<div/>', {
                'class': 'trigger_rule'
            }).appendTo(holder); 

            $trigger_rule.append([
                '<a href="javascript:void(0)" class="remove_trigger_rule btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a>',
                '<table>',
                    '<tr>',
                        '<td class="trigger_logic_holder" style="width: 100px; padding: 5px; vertical-align: middle;">',
                            '<div class="trigger_logic">',
                                '<select class="form-control">',
                                    '<option value="intersect">и</option>',
                                    '<option value="merge">или</option>',
                                '</select>',
                            '</div>',
                        '</td>', 
                        '<td class="trigger_holder">',
                            '<div class="trigger_rules"></div>',
                            '<div class="trigger_children"></div>',
                            '<div class="btn-group">',
                                '<div class="btn-group">',
                                    '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">и</button>',
                                    '<ul class="dropdown-menu" role="menu">',
                                        '<li><a class="add_trigger_rule" data-logic="intersect" data-method="send_mail" href="javascript:void(0)">Отправка письма</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="intersect" data-method="tunnel_label" href="javascript:void(0)">Метка процесса</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="intersect" data-method="letter_click" href="javascript:void(0)">Клик в письме</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="intersect" data-method="letter_open" href="javascript:void(0)">Открытие письма</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="intersect" data-method="product_availability" href="javascript:void(0)">Доступность продукта</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="intersect" data-method="tunnels_users" href="javascript:void(0)">Участие в процессе</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="intersect" data-method="user_sale" href="javascript:void(0)">Доступность распродажи</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="intersect" data-method="exist_queue_processes" href="javascript:void(0)">Нет туннелей в очереди</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="intersect" data-method="user_not_processes" href="javascript:void(0)">Не проходят туннели (по типам)</a></li>',
                                    '</ul>',
                                '</div>',
                                '<div class="btn-group">',
                                    '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">или</button>',
                                    '<ul class="dropdown-menu" role="menu">',
                                        '<li><a class="add_trigger_rule" data-logic="merge" data-method="send_mail" href="javascript:void(0)">Отправка письма</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="merge" data-method="tunnel_label" href="javascript:void(0)">Метка процесса</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="merge" data-method="letter_click" href="javascript:void(0)">Клик в письме</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="merge" data-method="letter_open" href="javascript:void(0)">Открытие письма</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="merge" data-method="product_availability" href="javascript:void(0)">Доступность продукта</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="merge" data-method="tunnels_users" href="javascript:void(0)">Участие в процессе</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="merge" data-method="user_sale" href="javascript:void(0)">Доступность распродажи</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="merge" data-method="exist_queue_processes" href="javascript:void(0)">Нет туннелей в очереди</a></li>',
                                        '<li><a class="add_trigger_rule" data-logic="merge" data-method="user_not_processes" href="javascript:void(0)">Не проходят туннели (по типам)</a></li>',
                                    '</ul>',
                                '</div>',
                            '</div>',
                        '</td>',
                    '<tr>',
                '</table>'
            ].join(''));

            $('.add_trigger_rule', $trigger_rule).on('click', methods.add_trigger_rule);

            $('.trigger_logic select', $trigger_rule).val(rules.logic).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
            $('.remove_trigger_rule').on('click', methods.remove_trigger_rule);

            if (rules.rules !== undefined) {
                $.each(rules.rules, function(index, rule) {
                    methods.render_rule(rule, $('.trigger_rules', $trigger_rule));
                });
            }

            if (rules.children !== undefined) {
                methods.render_rules(rules.children, $('.trigger_children', $trigger_rule));
            }
        },
        render_rule: function(rule, holder) {
            var elid = Math.floor(Math.random( ) * (999999+1));
            
            $trigger_rule_item = $('<div/>', {
                'class': 'trigger_rule_item',
                'data-method': rule.method
            }).appendTo(holder);

            $trigger_rule_item.append([
                '<div class="trigger_settings">',
                    '<a href="javascript:void(0)" class="remove_rule_item btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a>',
                '</div>'
            ].join(''));

            $('.remove_rule_item').on('click', methods.remove_rule_item);

            switch(rule.method) {
                case 'user_not_processes':
                    $('.trigger_settings', $trigger_rule_item).append([
                        'Не получает&nbsp;',
                        '<select class="selectpicker" data-id="type" id="tunnels_users_type_' + elid + '" title="Выберите значение...">',
                            '<option value="static">статический</option>',
                            '<option value="dynamic">динамический</option>',
                        '</select>',
                        '&nbsp;туннель'
                    ].join(''));

                    $('body').on('change', $('#tunnels_users_type_' + elid), function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    if (rule.settings.type !== undefined) $('#tunnels_users_type_' + elid).val(rule.settings.type);
                    
                    $('#tunnels_users_type_' + elid)
                    .selectpicker({
                        width: '140px',
                        liveSearch: false
                    });
                    break;
                case 'exist_queue_processes':
                    $('.trigger_settings', $trigger_rule_item).append('Нет туннелей в очереди');
                    break;
                case 'user_sale':
                    $('.trigger_settings', $trigger_rule_item).append([
                        'Доступность распродажи',
                        '<input data-id="value" type="hidden" class="form-control" style="width: 300px; display: inline-block;" value="4,6,8,10,12,19,13,14,15,16,17">',
                    ].join(''));
                    break;
                case 'tunnels_users':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>',
                                '<td>',
                                    '<select class="selectpicker" data-id="type" id="tunnels_users_type_' + elid + '" title="Выберите значение...">',
                                        '<option value="exist">Есть подписка на</option>',
                                        '<option value="not_exist">Нет подписки на</option>',
                                        '<option value="active">Активная подписка на</option>',
                                        '<option value="complete">Пройденный туннель</option>',
                                        '<option value="pause">На паузе</option>',
                                        '<option value="permanent_pause">На постоянной паузе</option>',
                                    '</select>',
                                '</td>',
                                '<td>',
                                    '<input id="tunnels_users_tunnels_' + elid + '" type="text" data-id="tunnel_id" />',
                                '<td>',
                            '</tr>',
                        '</table>'
                    ].join(''));
                    
                    if (rule.settings.value !== undefined) $('#tunnels_users_tunnels_selector_' + elid).val(rule.settings.value);
                    $(document).on('change', $('#tunnels_users_tunnels_selector_' + elid), function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    $('#tunnels_users_tunnels_' + elid).TunnelSelector({'url':settings.url});
                    
                    if (rule.settings.type !== undefined) $('#tunnels_users_type_' + elid).val(rule.settings.type);
                    $(document).on('change', $('#tunnels_users_type_' + elid), function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    $('#tunnels_users_type_' + elid)
                    .selectpicker({
                        width: '190px',
                        liveSearch: false
                    });
                    break;
                case 'product_availability':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Доступность продукта</tr>',
                            '<tr>',
                                '<td id="select_filter_values">',
                                    'Загрузка..',
                                '<td>',
                                '<td>',
                                    '<input data-id="mode" class="form-control" type="hidden" value="any">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    var data = {
                        search : '{"logic" :"intersect","rules" :[{"method":"name","settings": {"logic":"LIKE","value":"%"}}]}',
                        sort_by: "id",
                        sort_direction: "desc",
                        current:1,
                        rows:-1
                    };
                    
                    $.ajax({
                        type: 'post',
                        url: settings.url + 'admin/billing/products/api/search.json',
                        data:JSON.stringify(data),
                        contentType: 'application/json',
                        success: function(resp) {
                            $('#select_filter_values', $trigger_rule_item).html('<select class="selectpicker" data-id="value" id="select_filter" title="Выберите значение..."></select>');
                            $.each(resp.rows,  function(index, value) {
                                if (value) $('#select_filter', $trigger_rule_item).append('<option value="' + value.id + '">' + value.name + ' (' + value.amount + ' руб.)</option> ');
                            });

                            $('#select_filter', $trigger_rule_item)
                            .selectpicker({
                                width: '300px',
                                liveSearch: true
                            });
                            
                            if (rule.settings.value !== undefined) $('#select_filter', $trigger_rule_item).selectpicker('val', rule.settings.value);
                            
                        }
                    });

                    $('body').on('change', $('.trigger_settings select[data-id="value"]', $trigger_rule_item), function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.mode !== undefined) $('.trigger_settings input[data-id="mode"]', $trigger_rule_item).val(rule.settings.mode);
                    $('.trigger_settings input[data-id="mode"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    break;
                case 'send_mail': 
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>',
                                '<td style="width: 160px; padding-right: 10px;">',
                                    '<select data-id="mode" class="form-control">',
                                        '<option value="exist">отправлено</option>',
                                        '<option value="not_exist">не отправлено</option>',
                                    '</select>',
                                '</td>',
                                '<td>',
                                    '<input data-id="letter" type="hidden">',
                                '</td>',
                            '</tr>',
                        '</table>',
                        '<div class="panel-group accordion" id="accordion' + elid + '">',
                            '<div class="panel">',
                                '<div class="panel-heading">',
                                    '<h4 class="panel-title">',
                                        '<a data-parent="#accordion' + elid + '" data-toggle="collapse" href="#add_settings' + elid + '">дополнительные условия</a>',
                                    '</h4>',
                                '</div>',
                                '<div class="panel-collapse collapse" id="add_settings' + elid + '">',
                                    '<div class="panel-body">',
                                        'Таймаут',
                                        '<input data-id="timeout-value" type="text" class="form-control" style="width: 80px; display: inline-block;">',
                                        '<select data-id="timeout-mode" class="form-control">',
                                            '<option value="min">мин.</option>',
                                            '<option value="hours">час.</option>',
                                            '<option value="days">дн.</option>',
                                        '</select>',
                                    '</div>',
                                '</div>',
                            '</div>',
                        '</div>'
                    ].join(''));

                    if (rule.settings.letter !== undefined) $('.trigger_settings input[data-id="letter"]', $trigger_rule_item).val(rule.settings.letter);
                    $('.trigger_settings input[data-id="letter"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))}).MailingLetterSelector({'url':settings.url});

                    if (rule.settings.mode !== undefined) $('.trigger_settings select[data-id="mode"]', $trigger_rule_item).val(rule.settings.mode);
                    $('.trigger_settings select[data-id="mode"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.timeout !== undefined) {
                        if (rule.settings.timeout.value !== undefined) $('.trigger_settings input[data-id="timeout-value"]', $trigger_rule_item).val(rule.settings.timeout.value);
                        if (rule.settings.timeout.mode !== undefined) $('.trigger_settings select[data-id="timeout-mode"]', $trigger_rule_item).val(rule.settings.timeout.mode);
                    }
                    
                    $('.trigger_settings input[data-id="timeout-value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    $('.trigger_settings select[data-id="timeout-mode"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                    
                case 'tunnel_label': 
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>',
                                '<td style="width: 160px; padding-right: 10px;">',
                                    '<select data-id="mode" class="form-control">',
                                        '<option value="exist">есть метка</option>',
                                        '<option value="not_exist">нет метки</option>',
                                    '</select>',
                                '</td>',
                                '<td>',
                                    '<input data-id="tunnel_id" type="text">',
                                '</td>',
                            '</tr>',
                            '<tr>',
                                '<td style="padding-right: 10px;">',
                                    '<br><input data-id="label_id" type="text" class="form-control">',
                                '</td>',
                                '<td>',
                                    '<br><input data-id="label_data" type="text" class="form-control">',
                                '</td>',
                            '</tr>',
                        '</table>',
                        '<div class="panel-group accordion" id="accordion' + elid + '">',
                            '<div class="panel">',
                                '<div class="panel-heading">',
                                    '<h4 class="panel-title">',
                                        '<a data-parent="#accordion' + elid + '" data-toggle="collapse" href="#add_settings' + elid + '">дополнительные условия</a>',
                                    '</h4>',
                                '</div>',
                                '<div class="panel-collapse collapse" id="add_settings' + elid + '">',
                                    '<div class="panel-body">',
                                        'Таймаут',
                                        '<input data-id="timeout-value" value="1" type="text" class="form-control" style="width: 80px; display: inline-block;">',
                                        '<select data-id="timeout-mode" class="form-control">',
                                            '<option value="min">мин.</option>',
                                            '<option value="hours">час.</option>',
                                            '<option value="days">дн.</option>',
                                        '</select>',
                                    '</div>',
                                '</div>',
                            '</div>',
                        '</div>'
                    ].join(''));
                    
                    
                            
                    if (rule.settings.mode !== undefined) $('.trigger_settings select[data-id="mode"]', $trigger_rule_item).val(rule.settings.mode);
                    $('.trigger_settings select[data-id="mode"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.tunnel_id !== undefined) $('.trigger_settings input[data-id="tunnel_id"]', $trigger_rule_item).val(rule.settings.tunnel_id);
                    $(document).on('change', $('.trigger_settings select[data-id="tunnel_id"]', $trigger_rule_item), function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    $('.trigger_settings input[data-id="tunnel_id"]', $trigger_rule_item).TunnelSelector({'url':settings.url});
                    
                    if (rule.settings.label_id !== undefined) $('.trigger_settings input[data-id="label_id"]', $trigger_rule_item).val(rule.settings.label_id);
                    $('.trigger_settings input[data-id="label_id"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    if (rule.settings.label_data !== undefined) $('.trigger_settings input[data-id="label_data"]', $trigger_rule_item).val(rule.settings.label_data);
                    $('.trigger_settings input[data-id="label_data"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    if (rule.settings.timeout !== undefined) { 
                        if (rule.settings.timeout.value !== undefined) $('.trigger_settings input[data-id="timeout-value"]', $trigger_rule_item).val(rule.settings.timeout.value);
                        if (rule.settings.timeout.mode !== undefined) $('.trigger_settings select[data-id="timeout-mode"]', $trigger_rule_item).val(rule.settings.timeout.mode);
                    }
                    
                    $('.trigger_settings input[data-id="timeout-value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    $('.trigger_settings select[data-id="timeout-mode"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                    
                case 'letter_click': 
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>',
                                '<td style="width: 160px; padding-right: 10px;">',
                                    '<select data-id="mode" class="form-control">',
                                        '<option value="exist">есть клик</option>',
                                        '<option value="not_exist">нет клика</option>',
                                    '</select>',
                                '</td>',
                                '<td>',
                                    '<input data-id="letter" type="hidden">',
                                '</td>',
                            '</tr>',
                        '</table>',
                        '<div class="panel-group accordion" id="accordion' + elid + '">',
                            '<div class="panel">',
                                '<div class="panel-heading">',
                                    '<h4 class="panel-title">',
                                        '<a data-parent="#accordion' + elid + '" data-toggle="collapse" href="#add_settings' + elid + '">дополнительные условия</a>',
                                    '</h4>',
                                '</div>',
                                '<div class="panel-collapse collapse" id="add_settings' + elid + '">',
                                    '<div class="panel-body">',
                                        'URL',
                                        '<input data-id="url" type="text" value="" class="form-control" style="width: 300px; display: inline-block;">',
                                        '<br>',
                                        'Таймаут',
                                        '<input data-id="timeout-value" value="1" type="text" class="form-control" style="width: 80px; display: inline-block;">',
                                        '<select data-id="timeout-mode" class="form-control">',
                                            '<option value="min">мин.</option>',
                                            '<option value="hours">час.</option>',
                                            '<option value="days">дн.</option>',
                                        '</select>',
                                    '</div>',
                                '</div>',
                            '</div>',
                        '</div>'
                    ].join(''));

                    if (rule.settings.letter !== undefined) $('.trigger_settings input[data-id="letter"]', $trigger_rule_item).val(rule.settings.letter);
                    $('.trigger_settings input[data-id="letter"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))}).MailingLetterSelector({'url':settings.url});

                    if (rule.settings.mode !== undefined) $('.trigger_settings select[data-id="mode"]', $trigger_rule_item).val(rule.settings.mode);
                    $('.trigger_settings select[data-id="mode"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.url !== undefined) $('.trigger_settings input[data-id="url"]', $trigger_rule_item).val(rule.settings.url);
                    $('.trigger_settings input[data-id="url"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    

                    if (rule.settings.timeout !== undefined) {
                        if (rule.settings.timeout.value !== undefined) $('.trigger_settings input[data-id="timeout-value"]', $trigger_rule_item).val(rule.settings.timeout.value);
                        if (rule.settings.timeout.mode !== undefined) $('.trigger_settings select[data-id="timeout-mode"]', $trigger_rule_item).val(rule.settings.timeout.mode);
                    }
                    
                    $('.trigger_settings input[data-id="timeout-value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    $('.trigger_settings select[data-id="timeout-mode"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                    
                case 'letter_open': 
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>',
                                '<td style="width: 160px; padding-right: 10px;">',
                                    '<select data-id="mode" class="form-control">',
                                        '<option value="exist">есть открытие</option>',
                                        '<option value="not_exist">нет открытия</option>',
                                    '</select>',
                                '</td>',
                                '<td>',
                                    '<input data-id="letter" type="hidden">',
                                '</td>',
                            '</tr>',
                        '</table>',
                        '<div class="panel-group accordion" id="accordion' + elid + '">',
                            '<div class="panel">',
                                '<div class="panel-heading">',
                                    '<h4 class="panel-title">',
                                        '<a data-parent="#accordion' + elid + '" data-toggle="collapse" href="#add_settings' + elid + '">дополнительные условия</a>',
                                    '</h4>',
                                '</div>',
                                '<div class="panel-collapse collapse" id="add_settings' + elid + '">',
                                    '<div class="panel-body">',
                                        'Таймаут',
                                        '<input data-id="timeout-value" type="text" class="form-control" style="width: 80px; display: inline-block;">',
                                        '<select data-id="timeout-mode" class="form-control">',
                                            '<option value="min">мин.</option>',
                                            '<option value="hours">час.</option>',
                                            '<option value="days">дн.</option>',
                                        '</select>',
                                    '</div>',
                                '</div>',
                            '</div>',
                        '</div>'
                    ].join(''));

                    if (rule.settings.letter !== undefined) $('.trigger_settings input[data-id="letter"]', $trigger_rule_item).val(rule.settings.letter);
                    $('.trigger_settings input[data-id="letter"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))}).MailingLetterSelector({'url':settings.url});

                    if (rule.settings.mode !== undefined) $('.trigger_settings select[data-id="mode"]', $trigger_rule_item).val(rule.settings.mode);
                    $('.trigger_settings select[data-id="mode"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.timeout !== undefined) {
                        if (rule.settings.timeout.value !== undefined) $('.trigger_settings input[data-id="timeout-value"]', $trigger_rule_item).val(rule.settings.timeout.value);
                        if (rule.settings.timeout.mode !== undefined) $('.trigger_settings select[data-id="timeout-mode"]', $trigger_rule_item).val(rule.settings.timeout.mode);
                    }
                    
                    $('.trigger_settings input[data-id="timeout-value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    $('.trigger_settings select[data-id="timeout-mode"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
            }
        }
    };

    $.fn.TriggerRulesEditor = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('TriggerRulesEditor: Unknown method: ' +  method);
        }
    };
})(jQuery);