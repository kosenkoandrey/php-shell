
(function($) {
    var active_group = 0;
    var sender_obj = new Object();
    var settings;

    var methods = {
        init: function(options) { 
            var sender_manager = $(this);

            settings = $.extend( {
                'debug': false,
                'url' : 'https://pult2.glamurnenko.ru/'
            }, options);

            $('<span/>', {
                'class': 'sender_info btn btn-default'
            }).insertAfter(sender_manager);

            if ((sender_manager.val() !== '') && (sender_manager.val() !== '0')) {
                sender_manager.next().html([
                '<span class="glyphicon glyphicon-user"></span>',
                '<span class="sender-name">',
                    'идет загрузка...', 
                '</span>'
                ].join('')); 
                
                $.ajax({
                    type: 'post',
                    url: settings.url +'mailing/admin/senders/api/list.json',
                    data: {
                        select: ['name'],
                        where: [
                            ['id', '=', sender_manager.val()]
                        ]
                    },
                    success: function(senders) {
                        $('> .sender-name', sender_manager.next()).html(senders[0].name);
                    }
                });
            } else {
                sender_manager.next().html([
                '<span class="glyphicon glyphicon-user"></span> ',
                '<span class="sender-name">',
                    'Выбрать отправителя', 
                '</span>'
                ].join(''));
            }

            sender_manager.next().on('click', methods.open_manager);
            
            return this;
        },
        open_manager: function() { 
            sender_obj = $(this);
            
            $('body').prepend([
                '<div class="modal-backdrop senders-manager fade in" style="z-index: 9000;"></div>',
                '<div id="senders-manager">',
                    '<div id="senders-panel" class="panel panel-default" data-target="#senders-panel">',
                        '<div class="panel-heading"><a href="javascript:void(0)" class="btn btn-danger btn-sm pull-right"><span class="glyphicon glyphicon-remove"></span></a><h3 class="panel-title">Выберите отправителя</h3></div>',
                        '<div class="panel-body">',
                            '<table id="senders-list" class="table table-hover">',
                                '<tbody></tbody>',
                            '</table>',
                        '</div>',
                        '<div class="panel-footer">',
                            '<div id="groups-tree" class="btn-group">',
                                '<a href="#" class="btn btn-default btn-sm active disabled">/</a>',
                            '</div>',
                        '</div>', 
                    '</div>',
                '</div>'
            ].join(''));
            
            $('#senders-panel > .panel-heading > a').on('click', methods.close_manager);
            $('body').on('click', '#groups-tree a', methods.get_group_tree);
            $('.modal-backdrop.senders-manager').css('height', $(document).height() + 'px');

            methods.get_tree(0);
        },
        close_manager: function() { 
            $('.modal-backdrop.senders-manager').remove();
            $('#senders-manager').remove();
        },
        select_sender: function() { 
            sender_obj.prev().val($(this).parent().data('sender')).trigger('paste');
            $('> .sender-name', sender_obj).html($(this).html());
            methods.close_manager();
        },
        get_group_tree: function() {
            console.log($(this));
            methods.get_tree($(this).data('group'));
        },
        get_group: function() { 
            methods.get_tree($(this).parent().data('group'));
        },
        get_tree: function(group) { 
            active_group = group;

            // Get group path
            $.ajax({
                type: 'post',
                url: settings.url +'mailing/admin/senders/api/groups/path.json',
                data: {
                    group: group
                },
                success: function(group_path) {
                    $('#groups-tree').empty();
                    $('#senders-list > tbody').empty();

                    $.each(group_path, function(group_id, group_name) {
                        if (group_id == group) {
                            $('#groups-tree').append('<a href="#" class="btn btn-default btn-sm active disabled">' + group_name + '</a>');
                        } else {
                            $('#groups-tree').append('<a href="#" data-group="' + group_id + '" class="btn btn-default btn-sm">' + group_name + '</a>');
                        }

                    });


                    // Get groups
                    $.ajax({
                        type: 'post',
                        url: settings.url +'mailing/admin/senders/api/groups/list.json',
                        data: {
                            select: ['id', 'name'],
                            where: [
                                ['sub_id', '=', group],
                                ['state', '=', 'active']
                            ]
                        },
                        success: function(groups) {
                            $.each(groups, function(group_index, group) {
                                $('#senders-list > tbody').append([
                                    '<tr data-group="' + group.id + '">',
                                        '<td class="item-icon"><span class="glyphicon glyphicon-folder-open"></span></td>',
                                        '<td class="item-group-name">' + group.name + '</td>',
                                    '</tr>'
                                ].join(''));
                                
                                $('#senders-list > tbody > tr[data-group="' + group.id + '"] > .item-group-name').on('click', methods.get_group);
                            });

                            // Get senders
                            $.ajax({
                                type: 'post',
                                url: settings.url +'mailing/admin/senders/api/list.json',
                                data: {
                                    select: ['id', 'name'],
                                    where: [
                                        ['group_id', '=', group],
                                        ['state', '=', 'active']
                                    ]
                                },
                                success: function(groups) {
                                    $.each(groups, function(sender_index, sender) {
                                        $('#senders-list > tbody').append([
                                            '<tr data-sender="' + sender.id + '">',
                                                '<td class="item-icon"><span class="glyphicon glyphicon-user"></span></td>',
                                                '<td class="item-sender-name">' + sender.name + '</td>',
                                            '</tr>'
                                        ].join(''));
                                        
                                        $('#senders-list > tbody > tr[data-sender="' + sender.id + '"] > .item-sender-name').on('click', methods.select_sender);
                                    });
                                }
                            });
                        }
                    });
                    
                }
            });
        }
    };

    $.fn.MailingSenderSelector = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('TriggerSendersEditor: Unknown method: ' +  method);
        } 
    };
})(jQuery);