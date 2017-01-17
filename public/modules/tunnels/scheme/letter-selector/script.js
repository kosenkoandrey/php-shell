/*
 * @author Selivanov Max <max@evildevel.com>
 */

(function($) {
    var active_group = 0;
    var letter_obj = new Object();
    var settings;
    
    var methods = {
        init: function(options) { 
            var letters_manager = $(this);

            settings = $.extend( {
                'debug': false,
                'url' : 'http://pult2.glamurnenko.ru',
                 callback: function() {}
            }, options);

            $('<span/>', {
                'class': 'letter_info'
            }).insertAfter(letters_manager);

            if ((letters_manager.val() !== '') && (letters_manager.val() !== '0')) {
                letters_manager.next().html([
                '<div class="btn-group">',
                    '<a href="#" target="_blank" class="letter-preview btn btn-default disabled"><span class="glyphicon glyphicon-envelope"></span></a>',
                    '<a href="javascript:void(0)" class="letter-subject btn btn-default">определение темы письма...</a>',
                '</div>'
                ].join('')); 
                
                $.ajax({
                    type: 'post',
                    url: settings.url + '/admin/mail/api/letters/get.json',
                    data: {
                        select: ['id', 'subject'],
                        where: [
                            ['id', '=', letters_manager.val()]
                        ]
                    },
                    success: function(letters) {
                        $('> .btn-group > .letter-preview', letters_manager.next()).attr('href', settings.url + '/admin/mail/letters/preview/' + letters[0].token).removeClass('disabled');
                        $('> .btn-group > .letter-subject', letters_manager.next()).html('<span class="label label-warning">' + letters[0].id + '</span> ' + letters[0].subject);
                    }
                });
            } else {
                letters_manager.next().html([
                '<div class="btn-group">',
                    '<a href="#" target="_blank" class="letter-preview btn btn-default disabled"><span class="glyphicon glyphicon-envelope"></span></a>',
                    '<a href="javascript:void(0)" class="letter-subject btn btn-default">выберите письмо</a>',
                '</div>'
                ].join(''));
            }

            $('> .btn-group > .letter-subject', letters_manager.next()).on('click', methods.open_manager);
            
            return this;
        },
        open_manager: function() { 
            letter_obj = $(this);
            
            $('body').prepend([
                '<div class="modal-backdrop letters-manager fade in" style="z-index: 9000;"></div>',
                '<div id="letters-manager">',
                    '<div id="letters-panel" class="panel panel-default" data-target="#letters-panel">',
                        '<div class="panel-heading"><a href="javascript:void(0)" class="btn btn-danger btn-sm pull-right"><span class="glyphicon glyphicon-remove"></span></a><h3 class="panel-title">Выберите письмо</h3></div>',
                        '<div class="panel-body">',
                            '<table id="letters-list" class="table table-hover">',
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
            
            $('#letters-panel > .panel-heading > a').on('click', methods.close_manager);
            $('body').on('click', '#groups-tree a', methods.get_group_tree);
            $('.modal-backdrop.letters-manager').css('height', $(document).height() + 'px');

            methods.get_tree(0);
        },
        close_manager: function() { 
            $('.modal-backdrop.letters-manager').remove();
            $('#letters-manager').remove();
        },
        select_letter: function() { 
            var token = $(this).parent().data('token');
            var letter_id = $(this).parent().data('letter');

            letter_obj.parent().parent().prev().val(letter_id).trigger('paste');
            letter_obj.prev().attr('href', settings.url + '/admin/mail/letters/preview/' + token).removeClass('disabled');
            letter_obj.html('<span class="label label-warning">' + letter_id + '</span> ' + $(this).html());
            methods.close_manager();
        },
        get_group_tree: function() {
            methods.get_tree($(this).data('group'));
        },
        get_group: function() { 
            methods.get_tree($(this).parent().data('group'));
        },
        get_tree: function(group) { 
            active_group = group;

            $.ajax({
                type: 'post',
                url: settings.url +'/admin/mail/api/letters/manage.json',
                data: {
                    group: group
                },
                success: function(data) {
                    $('#groups-tree').empty();
                    $('#letters-list > tbody').empty();

                    $.each(data.path, function(index, item) {
                        if (item[0] == group) {
                            $('#groups-tree').append('<a href="#" class="btn btn-default btn-sm active disabled">' + item[1] + '</a>');
                        } else {
                            $('#groups-tree').append('<a href="#" data-group="' + item[0] + '" class="btn btn-default btn-sm">' + item[1] + '</a>');
                        }
                    });
                    
                    $.each(data.list, function(index, item) {
                        switch(item[0]) {
                            case 'group':
                                $('#letters-list > tbody').append([
                                    '<tr data-group="' + item[1] + '">',
                                        '<td class="item-icon"><span class="glyphicon glyphicon-folder-open"></span></td>',
                                        '<td class="item-group-name">' + item[2] + '</td>',
                                    '</tr>'
                                ].join(''));

                                $('#letters-list > tbody > tr[data-group="' + item[1] + '"] > .item-group-name').on('click', methods.get_group);
                                break;
                            case 'letter':
                                $('#letters-list > tbody').append([
                                    '<tr data-token="' + item[1] + '" data-letter="' + item[2] + '">',
                                        '<td class="item-icon"><span class="glyphicon glyphicon-envelope"></span></td>',
                                        '<td class="item-letter-subject">' + item[3] + '</td>',
                                    '</tr>'
                                ].join(''));

                                $('#letters-list > tbody > tr[data-letter="' + item[2] + '"] > .item-letter-subject').on('click', methods.select_letter);
                                break;
                        }
                    });
                }
            });
        }
    };

    $.fn.MailingLetterSelector = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('LetterSelector: Unknown method: ' +  method);
        } 
    };
})(jQuery);