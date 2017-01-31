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

            settings = $.extend( {
                'utl': 'http://pult2.glamurnenko.ru/',
                'rules': $.evalJSON($(this).val()),
                'debug': false
            }, options);

            $('<div/>', {
                'id': 'trigger_rules_editor',
            }).insertAfter(this);

            $('<div/>', {
                'class': 'trigger_children',
            }).appendTo($('#trigger_rules_editor'));

            methods.render_rules(settings.rules, $('.trigger_children'));
            methods.checkblock();
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
                                case 'email': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'role': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'id': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'source': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'state': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'firstname': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'lastname': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'city': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'country': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'tel': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'reg_date': 
                                    switch(id) {
                                        case 'date_from': 
                                            if (settings.date_from === undefined) settings.date_from = new Object();
                                            settings.date_from = param_value; 
                                            break;
                                        case 'date_to': 
                                            if (settings.date_to === undefined) settings.date_to = new Object();
                                            settings.date_to = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'social_id': 
                                    switch(id) {
                                        case 'service': 
                                            if (settings.service === undefined) settings.service = new Object();
                                            settings.service = param_value; 
                                            break;
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'utm': 
                                    switch(id) {
                                        case 'item': 
                                            if (settings.item === undefined) settings.item = new Object();
                                            settings.item = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        case 'num': 
                                            if (settings.num === undefined) settings.num = new Object();
                                            settings.num = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'tags': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        case 'date_from': 
                                            if (settings.date_from === undefined) settings.date_from = new Object();
                                            settings.date_from = param_value; 
                                            break;
                                        case 'date_to': 
                                            if (settings.date_to === undefined) settings.date_to = new Object();
                                            settings.date_to = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'tunnels': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'tunnels_type': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'tunnels_tags': 
                                    switch(id) {
                                        case 'token': 
                                            if (settings.token === undefined) settings.token = new Object();
                                            settings.token = param_value; 
                                            break;
                                        case 'label': 
                                            if (settings.label === undefined) settings.label = new Object();
                                            settings.label = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'tunnels_queue': 
                                    switch(id) {
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'tunnels_object': 
                                    switch(id) {
                                        case 'object': 
                                            if (settings.object === undefined) settings.object = new Object();
                                            settings.object = param_value; 
                                            break;
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'tunnels_label': 
                                    switch(id) {
                                        case 'label_id': 
                                            if (settings.label_id === undefined) settings.label_id = new Object();
                                            settings.label_id = param_value; 
                                            break;
                                        case 'token': 
                                            if (settings.token === undefined) settings.token = new Object();
                                            settings.token = param_value; 
                                            break;
                                        case 'cr_date_mode': 
                                            if (settings.cr_date_mode === undefined) settings.cr_date_mode = new Object();
                                            settings.cr_date_mode = param_value; 
                                            break;
                                        case 'cr_date_value': 
                                            if (settings.cr_date_value === undefined) settings.cr_date_value = new Object();
                                            settings.cr_date_value = param_value; 
                                            break;
                                        case 'process_id': 
                                            if (settings.process_id === undefined) settings.process_id = new Object();
                                            settings.process_id = param_value; 
                                            break;
                                        case 'mode': 
                                            if (settings.mode === undefined) settings.mode = new Object();
                                            settings.mode = param_value; 
                                            break;
                                        case 'from': 
                                            if (settings.from === undefined) settings.from = new Object();
                                            settings.from = param_value; 
                                            break;
                                        case 'to': 
                                            if (settings.to === undefined) settings.to = new Object();
                                            settings.to = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'mail_count': 
                                    switch(id) {
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'mail_events': 
                                    switch(id) {
                                        case 'value': 
                                            if (settings.value === undefined) settings.value = new Object();
                                            settings.value = param_value; 
                                            break;
                                        case 'logic': 
                                            if (settings.logic === undefined) settings.logic = new Object();
                                            settings.logic = param_value; 
                                            break;
                                        case 'date_from': 
                                            if (settings.date_from === undefined) settings.date_from = new Object();
                                            settings.date_from = param_value; 
                                            break;
                                        case 'date_to': 
                                            if (settings.date_to === undefined) settings.date_to = new Object();
                                            settings.date_to = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'mail_open_pct': 
                                    switch(id) {
                                        case 'from': 
                                            if (settings.from === undefined) settings.from = new Object();
                                            settings.from = param_value; 
                                            break;
                                        case 'to': 
                                            if (settings.to === undefined) settings.to = new Object();
                                            settings.to = param_value; 
                                            break;
                                        default: settings[id] = param_value;
                                    }
                                    break;
                                case 'mail_open_pct30': 
                                    switch(id) {
                                        case 'from': 
                                            if (settings.from === undefined) settings.from = new Object();
                                            settings.from = param_value; 
                                            break;
                                        case 'to': 
                                            if (settings.to === undefined) settings.to = new Object();
                                            settings.to = param_value; 
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

            $('select', trigger_rule).selectpicker(); 
            $target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))));
            methods.checkblock();
            methods.check_editor();
        },
        remove_trigger_rule: function() {
            $(this).parent().parent().remove();
            $(this).siblings('.trigger_rule_item').remove();
            $target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))));
            methods.checkblock();
            methods.check_editor();
        },
        checkblock: function() {
            var value = $target_rules.val();
            var obj = JSON.parse(value);

            if (obj.rules === undefined || obj.rules.length == 1) {
                $('.trigger_logic_holder').css('width', '0');
                $('.trigger_logic_holder', $trigger_rule).hide();
            } else if (obj.rules && obj.rules.length > 1) {
                $('.trigger_logic_holder').css('width', '100px');
                $('.trigger_logic_holder', $trigger_rule).show();
            }
        },
        getRulesListByLogic: function(logic) {
            return [
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="email" href="javascript:void(0)">Email</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="role" href="javascript:void(0)">Role</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="id" href="javascript:void(0)">ID</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="state" href="javascript:void(0)">State</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="reg_date" href="javascript:void(0)">Date registration</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="tags" href="javascript:void(0)">Tags</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="source" href="javascript:void(0)">Source</a></li>',
                '<li class="divider"></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="firstname" href="javascript:void(0)">Firstname</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="lastname" href="javascript:void(0)">Lastname</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="tel" href="javascript:void(0)">Telephone</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="city" href="javascript:void(0)">City</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="country" href="javascript:void(0)">Country</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="social_id" href="javascript:void(0)">Social ID</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="utm" href="javascript:void(0)">Utm</a></li>',
                '<li class="divider"></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="tunnels" href="javascript:void(0)">Tunnels</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="tunnels_type" href="javascript:void(0)">Tunnels type</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="tunnels_tags" href="javascript:void(0)">Tunnels tags</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="tunnels_queue" href="javascript:void(0)">Tunnels queue</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="tunnels_object" href="javascript:void(0)">Tunnels object</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="tunnels_label" href="javascript:void(0)">Tunnels label</a></li>',
                '<li class="divider"></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="mail_count" href="javascript:void(0)">Letter count</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="mail_events" href="javascript:void(0)">Letter event</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="mail_open_pct" href="javascript:void(0)">Letter open pct</a></li>',
                '<li><a class="add_trigger_rule" data-logic="' + logic + '" data-method="mail_open_pct30" href="javascript:void(0)">Letter open pct 30</a></li>'
            ].join('');
        },
        render_rules: function(rules, holder) {
            var self = this;

            $trigger_rule = $('<div/>', {
                'class': 'trigger_rule'
            }).appendTo(holder); 

            $trigger_rule.append([
                '<a style="display:none;" href="javascript:void(0)" class="remove_trigger_rule btn btn-default btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-close"></i></a>',
                '<table>',
                    '<tr>',
                        '<td class="trigger_logic_holder" style="width: 100px; vertical-align: middle;">',
                            '<div class="trigger_logic">',
                                '<select class="selectpicker" data-width="85px">',
                                    '<option value="intersect">AND</option>',
                                    '<option value="merge">OR</option>',
                                '</select>',
                            '</div>',
                        '</td>', 
                        '<td class="trigger_holder">',
                            '<div class="trigger_rules"></div>',
                            '<div class="trigger_children"></div>',
                            '<div class="btn-group trigger_holder_controls_several">',
                                '<div class="btn-group">',
                                    '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">AND</button>',
                                    '<ul class="dropdown-menu scrollable-menu scrollbar" role="menu">',                                  
                                        self.getRulesListByLogic('intersect'),
                                    '</ul>',
                                '</div>',
                                '<div class="btn-group">',
                                    '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">OR</button>',
                                    '<ul class="dropdown-menu scrollable-menu scrollbar" role="menu">',
                                        self.getRulesListByLogic('merge'),
                                    '</ul>',
                                '</div>',
                            '</div>',
                            '<div class="btn-group trigger_holder_controls_single">',
                                '<div class="btn-group">',
                                    '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Add condition</button>',
                                    '<ul class="dropdown-menu scrollable-menu scrollbar" role="menu">',                                  
                                        self.getRulesListByLogic('intersect'),
                                    '</ul>',
                                '</div>',
                            '</div>',
                        '</td>',
                    '<tr>',
                '</table>'
            ].join(''));
            $('.trigger_holder .remove_trigger_rule').show();

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
            
            $('.selectpicker').selectpicker(); 

            methods.check_editor();
        },
        check_editor: function() {
            $('.trigger_children').each(function (i, item) {
                var child_numbers = 0;
                $item = $(item);
                child_numbers += $item.find('.trigger_holder').first().find('.trigger_rules > div').length;
                child_numbers += $item.find('.trigger_holder').first().find('.trigger_children > div').length;
                if (child_numbers < 2) {
                    $(item).find('.trigger_logic').hide();
                    $(item).find('.trigger_logic_holder').hide();
                    $(item).find('.trigger_holder_controls_single').show();
                    $(item).find('.trigger_holder_controls_several').hide();
                } else {
                    $(item).find('.trigger_logic').show();
                    $(item).find('.trigger_logic_holder').show();
                    $(item).find('.trigger_holder_controls_single').hide();
                    $(item).find('.trigger_holder_controls_several').show(); 
                }
            })
        },
        render_rule: function(rule, holder) {
            var elid = Math.floor(Math.random( ) * (999999+1));
            
            $trigger_rule_item = $('<div/>', {
                'class': 'trigger_rule_item',
                'data-method': rule.method
            }).appendTo(holder);

            $trigger_rule_item.append([
                '<div class="trigger_settings">',
                    '<a href="javascript:void(0)" class="remove_rule_item btn btn-default btn-icon waves-effect waves-circle waves-float"><i class="zmdi zmdi-close"></i></a>',
                '</div>'
            ].join(''));

            $('.remove_rule_item').on('click', methods.remove_trigger_rule);

            switch(rule.method) {
                case 'email':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Email</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="logic">',
                                        '<option value="=">equal</option>',
                                        '<option value="!=">not equal</option>',
                                        '<option value="IN">in</option>',
                                        '<optgroup label="mask">',
                                            '<option value="LIKE">equal</option>',
                                            '<option value="NOT LIKE">not equal</option>',
                                        '</optgroup>',
                                        '<optgroup label="regexp">',
                                            '<option value="REGEXP">equal</option>',
                                            '<option value="NOT REGEXP">not equal</option>',
                                        '</optgroup>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'role':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Role</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<input data-id="logic" class="form-control m-l-5" type="hidden" value="=" />',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings input[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings input[data-id="logic"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'id':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>ID</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<input data-id="logic" class="form-control m-l-5" type="hidden" value="=" />',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings input[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings input[data-id="logic"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'source':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Source</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker form-control" data-id="logic">',
                                        '<option value="=">equal</option>',
                                        '<option value="!=">not equal</option>',
                                        '<option value="IN">in</option>',
                                    '</select>',
                                '</td>',
                                '<td>',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'state':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>State</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker form-control" data-id="logic">',
                                        '<option value="=">equal</option>',
                                        '<option value="!=">not equal</option>',
                                        '<option value="IN">in</option>',
                                    '</select>',
                                '</td>',
                            '</tr>',
                            '<tr>',
                                '<td>state</td>',
                                '<td style="width: 125px">',
                                    '<select data-id="value" class="form-control selectpicker">',
                                        '<option value="active">active</option>',
                                        '<option value="inactive">inactive</option>',
                                        '<option value="blacklist">blacklist</option>',
                                        '<option value="dropped">dropped</option>',
                                    '</select>',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings select[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings select[data-id="value"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'firstname':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Firstname</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="logic">',
                                        '<option value="=">equal</option>',
                                        '<option value="!=">not equal</option>',
                                        '<option value="IN">in</option>',
                                        '<optgroup label="mask">',
                                            '<option value="LIKE">equal</option>',
                                            '<option value="NOT LIKE">not equal</option>',
                                        '</optgroup>',
                                        '<optgroup label="regexp">',
                                            '<option value="REGEXP">equal</option>',
                                            '<option value="NOT REGEXP">not equal</option>',
                                        '</optgroup>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'lastname':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Lastname</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="logic">',
                                        '<option value="=">equal</option>',
                                        '<option value="!=">not equal</option>',
                                        '<option value="IN">in</option>',
                                        '<optgroup label="mask">',
                                            '<option value="LIKE">equal</option>',
                                            '<option value="NOT LIKE">not equal</option>',
                                        '</optgroup>',
                                        '<optgroup label="regexp">',
                                            '<option value="REGEXP">equal</option>',
                                            '<option value="NOT REGEXP">not equal</option>',
                                        '</optgroup>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'tel':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Telephone</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="logic">',
                                        '<option value="=">equal</option>',
                                        '<option value="!=">not equal</option>',
                                        '<option value="IN">in</option>',
                                        '<optgroup label="mask">',
                                            '<option value="LIKE">equal</option>',
                                            '<option value="NOT LIKE">not equal</option>',
                                        '</optgroup>',
                                        '<optgroup label="regexp">',
                                            '<option value="REGEXP">equal</option>',
                                            '<option value="NOT REGEXP">not equal</option>',
                                        '</optgroup>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'city':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>City</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="logic">',
                                        '<option value="=">equal</option>',
                                        '<option value="!=">not equal</option>',
                                        '<option value="IN">in</option>',
                                        '<optgroup label="mask">',
                                            '<option value="LIKE">equal</option>',
                                            '<option value="NOT LIKE">not equal</option>',
                                        '</optgroup>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'country':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Country</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="logic">',
                                        '<option value="=">equal</option>',
                                        '<option value="!=">not equal</option>',
                                        '<option value="IN">in</option>',
                                        '<optgroup label="mask">',
                                            '<option value="LIKE">equal</option>',
                                            '<option value="NOT LIKE">not equal</option>',
                                        '</optgroup>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'reg_date':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Date registration</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<input data-id="date_from" class="form-control m-l-5 date-picker" type="text" placeholder="date from">',
                                    '<input data-id="date_to" class="form-control m-l-5 date-picker" type="text" placeholder="date to">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));
                    
                    $('.date-picker', $trigger_rule_item).datetimepicker({
                        format: 'YYYY-MM-DD',
                        defaultDate: new Date()
                    }).on('dp.change', function(e){
                        $target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))));
                    });
                    break;
                case 'social_id':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>VK ID</tr>',
                            '<tr>',
                                '<td style="width: 220px">',
                                    '<select data-id="service">',
                                        '<option value="vk">vk</option>',
                                        '<option value="fb">facebook</option>',
                                        '<option value="ya">yandex</option>',
                                        '<option value="google">google</option>',
                                    '</select>',
                                '</td>',
                            '</tr>',
                            '<tr>',
                                '<td style="width: 220px">',
                                    '<input data-id="logic" class="form-control m-l-5" type="hidden" value="=" />',
                                    '<input data-id="value" class="form-control m-l-5" type="text" placeholder="ID">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));
                    
                    if (rule.settings.service !== undefined) $('.trigger_settings select[data-id="service"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="service"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.logic !== undefined) $('.trigger_settings input[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings input[data-id="logic"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'utm':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>',
                                '<td style="width: 100px">',
                                    'UTM<br>',
                                    '<select data-id="num">',
                                        '<option value="1">Первичная</option>',
                                        '<option value="0">Все</option>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 100px">',
                                    '<br>',
                                    '<select data-id="item">',
                                        '<option value="source">source</option>',
                                        '<option value="medium">medium</option>',
                                        '<option value="campaign">campaign</option>',
                                        '<option value="term">term</option>',
                                        '<option value="content">content</option>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 200px">',
                                    '<br><input data-id="value" class="form-control" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));
                    
                    if (rule.settings.num !== undefined) $('.trigger_settings select[data-id="num"]', $trigger_rule_item).val(rule.settings.num);
                    $('body').on('change', $('.trigger_settings select[data-id="num"]', $trigger_rule_item), function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    if (rule.settings.item !== undefined) $('.trigger_settings select[data-id="item"]', $trigger_rule_item).val(rule.settings.name);
                    $('body').on('change', $('.trigger_settings select[data-id="name"]', $trigger_rule_item), function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    break;
                case 'tags':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Tags</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="logic">',
                                        '<option value="exist">exist</option>',
                                        '<option value="not_exist">not_exist</option>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text" placeholder="tag">',
                                '</td>',
                            '</tr>',
                            '<tr>',
                                '<td style="width: 100px">',
                                    '<input data-id="date_from" class="form-control m-l-5 date-picker" type="text" placeholder="date from">',
                                '</td>',
                                '<td style="width: 100px">',
                                    '<input data-id="date_to" class="form-control m-l-5 date-picker" type="text" placeholder="date to">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));
                    
                    $('.date-picker', $trigger_rule_item).datetimepicker({
                        format: 'YYYY-MM-DD',
                        defaultDate: new Date()
                    }).on('dp.change', function(e){
                        $target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))));
                    });
                    
                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    break;
                case 'tunnels':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Tunnels</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select data-id="logic">',
                                        '<option value="exist">Подписка на туннель</option>',
                                        '<option value="not_exist">Нет подписки на процесс</option>',
                                        '<option value="active">Туннель активный</option>',
                                        '<option value="complete">Туннель закончен</option>',
                                        '<option value="pause">Туннель на паузе</option>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 200px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));
                    
                    if (rule.settings.tunnel_id !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.tunnel_id);
                    $(document).on('change', $('.trigger_settings select[data-id="value"]', $trigger_rule_item), function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).TunnelSelector({'url':settings.url});

                    if (rule.settings.value !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'tunnels_type':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Tunnels type</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="logic">',
                                        '<option value="IN">in</option>',
                                        '<option value="NOT IN">not in</option>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="value">',
                                        '<option value="static">static</option>',
                                        '<option value="dynamic">dynamic</option>',
                                    '</select>',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="value"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="value"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    break;
                case 'tunnels_tags':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Tunnels tags</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<input data-id="token" class="form-control m-l-5" type="text" placeholder="token">',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="label" class="form-control m-l-5" type="text" placeholder="label">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="token"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="token"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="label"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="label"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'tunnels_queue':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Tunnels queue</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker" data-id="logic">',
                                        '<option value="IN">in</option>',
                                        '<option value="NOT IN">not in</option>',
                                    '</select>',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    break;
                case 'tunnels_object':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Tunnels object</tr>',
                            '<tr>',
                                '<td style="width: 200px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text" placeholder="tunnel_id">',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="object" class="form-control m-l-5" type="text" placeholder="object">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.tunnel_id !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.tunnel_id);
                    $(document).on('change', $('.trigger_settings select[data-id="value"]', $trigger_rule_item), function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).TunnelSelector({'url':settings.url});
                    
                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="object"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="object"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
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
                case 'mail_count':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Letter count</tr>',
                            '<tr>',
                                '<td style="width: 220px">',
                                    '<select data-id="logic" class="form-control">',
                                        '<option value="=">=</option>',
                                        '<option value="!=">!=</option>',
                                        '<option value=">">></option>',
                                        '<option value="<"><</option>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="value" class="form-control m-l-5" type="text">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.value !== undefined) $('.trigger_settings input[data-id="value"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="value"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'mail_events':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Letter events</tr>',
                            '<tr>',
                                '<td style="width: 125px">',
                                    '<input data-id="date_from" class="form-control date-picker" type="text">',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<input data-id="date_to" class="form-control date-picker" type="text" >',
                                '</td>',
                            '</tr>',
                            '<tr>',
                                '<td style="width: 100px">',
                                    '<select class="selectpicker form-control" data-id="logic" >',
                                        '<option value="=">=</option>',
                                        '<option value="!=">!=</option>',
                                    '</select>',
                                '</td>',
                                '<td style="width: 125px">',
                                    '<select class="selectpicker form-control" data-id="value">',
                                        '<option value="open">open</option>',
                                        '<option value="click">click</option>',
                                        '<option value="delivered">delivered</option>',
                                        '<option value="processed">processed</option>',
                                        '<option value="unsubscribe">unsubscribe</option>',
                                        '<option value="dropped">dropped</option>',
                                        '<option value="deferred">deferred</option>',
                                        '<option value="bounce">bounce</option>',
                                        '<option value="spamreport">spamreport</option>',
                                    '</select>',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));


                    $('.date-picker', $trigger_rule_item).datetimepicker({
                        format: 'YYYY-MM-DD',
                        defaultDate: new Date()
                    }).on('dp.change', function(e){
                        $target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))));
                    });

                    if (rule.settings.value !== undefined) $('.trigger_settings select[data-id="value"]', $trigger_rule_item).val(rule.settings.action);
                    $('.trigger_settings select[data-id="value"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.logic !== undefined) $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).val(rule.settings.mode);
                    $('.trigger_settings select[data-id="logic"]', $trigger_rule_item).on('change', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    break;
                case 'mail_open_pct':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Letter open pct</tr>',
                            '<tr>',
                                '<td style="width: 100px">',
                                    '<input data-id="from" class="form-control m-l-5" type="text" placeholder="0">',
                                '</td>',
                            '</tr>',
                            '<tr>',
                                '<td style="width: 100px">',
                                    '<input data-id="to" class="form-control m-l-5" type="text" placeholder="95">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.from !== undefined) $('.trigger_settings select[data-id="from"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings input[data-id="from"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.to !== undefined) $('.trigger_settings input[data-id="to"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="to"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
                case 'mail_open_pct30 ':
                    $('.trigger_settings', $trigger_rule_item).append([
                        '<table>',
                            '<tr>Letter open pct 30</tr>',
                            '<tr>',
                                '<td style="width: 100px">',
                                    '<input data-id="from" class="form-control m-l-5" type="text" placeholder="0">',
                                '</td>',
                            '</tr>',
                            '<tr>',
                                '<td style="width: 100px">',
                                    '<input data-id="to" class="form-control m-l-5" type="text" placeholder="95">',
                                '</td>',
                            '</tr>',
                        '</table>'
                    ].join(''));

                    if (rule.settings.from !== undefined) $('.trigger_settings select[data-id="from"]', $trigger_rule_item).val(rule.settings.logic);
                    $('.trigger_settings input[data-id="from"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});

                    if (rule.settings.to !== undefined) $('.trigger_settings input[data-id="to"]', $trigger_rule_item).val(rule.settings.value);
                    $('.trigger_settings input[data-id="to"]', $trigger_rule_item).on('input propertychange paste', function(){$target_rules.val($.toJSON(methods.render_value($('#trigger_rules_editor > .trigger_children > .trigger_rule'))))});
                    
                    break;
            }
        }
    };

    $.fn.RefRulesEditor = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('RefRulesEditor: Unknown method: ' +  method);
        }
    };
})(jQuery);