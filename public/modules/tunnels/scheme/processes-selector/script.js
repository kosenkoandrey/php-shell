/*
 * @author Selivanov Max <max@evildevel.com>
 */

(function($) {
    var active_group = 0;
    var process_obj = new Object();
    var settings;

    var methods = {
        init: function(options) { 
            var process_manager = $(this);

            settings = $.extend({
                debug: false,
                url : 'https://pult.glamurnenko.ru',
                callback: null,
                'tunnel_link': true,
                'button_icon': 'glyphicon glyphicon-tasks',
                'button_size': '',
                'default_button_text': 'выберите туннель'
            }, options);

            $('<span/>', {
                'class': 'process_info'
            }).insertAfter(process_manager);

            if ((process_manager.val() !== '') && (process_manager.val() !== '0')) {
                process_manager.next().html([
                '<div class="btn-group">',
                    settings.tunnel_link ? '<a href="#" target="_blank" class="process-scheme btn btn-default disabled ' + settings.button_size + '"><span class="' + settings.button_icon + '"></span></a>' : '',
                    '<a href="javascript:void(0)" class="process-name btn btn-default ' + settings.button_size + '">определение наименования туннеля...</a>',
                '</div>'
                ].join('')); 
                
                $.ajax({
                    type: 'post',
                    url: settings.url + '/processes/admin/api/list.json',
                    data: {
                        select: ['id', 'name'],
                        where: [
                            ['id', '=', process_manager.val()]
                        ]
                    },
                    success: function(processes) {
                        if (settings.tunnel_link) $('> .btn-group > .process-scheme', process_manager.next()).attr('href', settings.url + '/processes/admin/scheme/' + processes[0].id).removeClass('disabled');
                        $('> .btn-group > .process-name', process_manager.next()).html('<span class="label label-warning">' + processes[0].id + '</span> ' + processes[0].name);
                        
                        if (settings.callback !== null) settings.callback(processes[0]);
                    }
                });
            } else {
                process_manager.next().html([
                '<div class="btn-group">',
                    settings.tunnel_link ? '<a href="#" target="_blank" class="process-scheme btn btn-default disabled ' + settings.button_size + '"><span class="' + settings.button_icon + '"></span></a>' : '',
                    '<a href="javascript:void(0)" class="process-name btn btn-default ' + settings.button_size + '">' + settings.default_button_text + '</a>',
                '</div>'
                ].join(''));
            }

            $('> .btn-group > .process-name', process_manager.next()).on('click', methods.open_manager);
            
            return this;
        },
        open_manager: function() { 
            process_obj = $(this);
            
            $('body').prepend([
                '<div class="modal-backdrop processes-manager fade in" style="z-index: 9000;"></div>',
                '<div id="processes-manager">',
                    '<div id="processes-panel" class="panel panel-default" data-target="#processes-panel">',
                        '<div class="panel-heading"><a href="javascript:void(0)" class="btn btn-danger btn-sm pull-right"><span class="glyphicon glyphicon-remove"></span></a><h3 class="panel-title">Выберите туннель</h3></div>',
                        '<div class="panel-body">',
                            '<table id="processes-list" class="table table-hover">',
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
            
            $('#processes-panel > .panel-heading > a').on('click', methods.close_manager);
            $('body').on('click', '#groups-tree a', methods.get_group_tree);
            $('.modal-backdrop.processes-manager').css('height', $(document).height() + 'px');

            methods.get_tree(0);
        },
        close_manager: function() { 
            $('.modal-backdrop.processes-manager').remove();
            $('#processes-manager').remove();
        },
        select_process: function() { 
            var process_id = $(this).parent().data('process');

            process_obj.parent().parent().prev().val(process_id).trigger('paste');
            if (settings.tunnel_link) process_obj.prev().attr('href', settings.url + '/processes/admin/scheme/' + process_id).removeClass('disabled');
            process_obj.html('<span class="label label-warning">' + process_id + '</span> ' + $(this).html());
            methods.close_manager();
            
            if (settings.callback !== null) settings.callback({
                id: process_id,
                name: $(this).html()
            });
        },
        get_group_tree: function() {
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
                url: settings.url + '/processes/admin/api/groups/path.json',
                data: {
                    group: group
                },
                success: function(group_path) {
                    $('#groups-tree').empty();
                    $('#processes-list > tbody').empty();

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
                        url: settings.url + '/processes/admin/api/groups/list.json',
                        data: {
                            select: ['id', 'name'],
                            where: [
                                ['sub_id', '=', group],
                                ['state', '=', 'active']
                            ]
                        },
                        success: function(groups) {
                            $.each(groups, function(group_index, group) {
                                $('#processes-list > tbody').append([
                                    '<tr data-group="' + group.id + '">',
                                        '<td class="item-icon"><span class="glyphicon glyphicon-folder-open"></span></td>',
                                        '<td class="item-group-name">' + group.name + '</td>',
                                    '</tr>'
                                ].join(''));
                                
                                $('#processes-list > tbody > tr[data-group="' + group.id + '"] > .item-group-name').on('click', methods.get_group);
                            });

                            // Get processes
                            $.ajax({
                                type: 'post',
                                url: settings.url + '/processes/admin/api/list.json',
                                data: {
                                    select: ['id', 'name'],
                                    where: [
                                        ['group_id', '=', group],
                                        ['state', '=', 'active']
                                    ]
                                },
                                success: function(groups) {
                                    $.each(groups, function(process_index, process) {
                                        $('#processes-list > tbody').append([
                                            '<tr data-process="' + process.id + '">',
                                                '<td class="item-icon"><span class="glyphicon glyphicon-tasks"></span></td>',
                                                '<td class="item-process-name">' + process.name + '</td>',
                                            '</tr>'
                                        ].join(''));
                                        
                                        $('#processes-list > tbody > tr[data-process="' + process.id + '"] > .item-process-name').on('click', methods.select_process);
                                    });
                                }
                            });
                        }
                    });
                    
                }
            });
        }
    };

    $.fn.TunnelSelector = function(method, callback) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        } else if (typeof callback == 'function') { 
			return methods.callback.call(this);
		} else {
            $.error('TunnelSelector: Unknown method: ' +  method);
        } 
    };
})(jQuery);