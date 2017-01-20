/*
 * @author Selivanov Max <max@evildevel.com>
 */

(function($) {
    var active_group = 0;
    var letter_obj = new Object();
    var settings;
    
    var methods = {
        init: function(options) { 
            var tunnel_selector = $(this);
            var data = tunnel_selector.data();
            
            settings = $.extend( {
                'debug': false,
                'url' : 'http://pult2.glamurnenko.ru/',
                 callback: function() {}
            }, options);
            
            var selector = $('<select/>', {
                'id': 'tunnel_id',
                'class': 'form-control'
            });
            
            $.ajax({
                type: 'post',
                url: settings.url + 'admin/tunnels/api/manage.json',
                success: function(tunnels) {
                    $.each(tunnels, function(index, item) {
                        if (item.id == tunnel_selector.val()) {
                            selector.append($('<option/>', {
                                'value': item.id
                            }).attr('selected','selected').html(item.name));
                        } else {
                            selector.append($('<option/>', {
                                'value': item.id
                            }).html(item.name));
                        }
                    });
                    
                    $.each(data, function(k, i){
                        selector.attr('data-'+k, i);
                    });
                    
                    tunnel_selector.replaceWith(selector);
                }
            });
            
            return this;
        }
    };

    $.fn.TunnelSelector = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('TunnelSelector: Unknown method: ' +  method);
        } 
    };
})(jQuery);