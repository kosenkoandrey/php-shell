<script>
    $(document).on('click', '#tab-nav-<?= $data['hash'] ?> > a, #refresh-tunnels',function() {
        $('#tunnels-list').html('<div class="text-center"><div class="preloader pl-xxl"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20" /></svg></div></div>');
        
        $.ajax({
            url: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/dashboard.json',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                $('#tunnels-list').html([
                    '<button id="refresh-tunnels" type="button" class="btn btn-default waves-effect pull-right"><i class="zmdi zmdi-refresh"></i> Обновить</button>',
                    '<div class="row m-b-20">',
                        '<div class="col-md-6 media-body ns-item">',
                            '<small class="c-black">Проходят туннели</small>',
                            '<h3 class="c-black">' + data.states.active + '</h3>',
                        '</div>',
                        '<div class="col-md-6 media-body ns-item">',
                            '<small class="c-black">Не проходят туннели</small>',
                            '<h3 class="c-black">' + data.states.inactive + '</h3>',
                        '</div>',
                    '</div>',
                    '<div class="table-responsive m-b-25">',
                        '<table id="static-tunnels-table" class="table table-hover">',
                            '<thead>',
                                '<tr>',
                                    '<th width="60%">Статические туннели</th>',
                                    '<th width="20%">Активные подписчики</th>',
                                    '<th width="20%">Всего подписчиков</th>',
                                '</tr>',
                            '</thead>',
                            '<tbody>',
                                '<tr class="total">',
                                    '<td>Итого</td>',
                                    '<td class="static_tunnels_active_total"><a href="#"></a></td>',
                                    '<td class="static_tunnels_total"><a href="#"></a></td>',
                                '</tr>',
                            '</tbody>',
                        '</table>',
                    '</div>',
                    '<div class="table-responsive">',
                        '<table id="dynamic-tunnels-table" class="table table-hover">',
                            '<thead>',
                                '<tr>',
                                    '<th width="60%">Динамические туннели</th>',
                                    '<th width="20%">Активные подписчики</th>',
                                    '<th width="20%">Всего подписчиков</th>',
                                '</tr>',
                            '</thead>',
                            '<tbody>',
                                '<tr class="total">',
                                    '<td>Итого</td>',
                                    '<td class="dynamic_tunnels_active_total"><a href="#"></a></td>',
                                    '<td class="dynamic_tunnels_total"><a href="#"></a></td>',
                                '</tr>',
                            '</tbody>',
                        '</table>',
                    '</div>'
                ].join(''));
                
                var c_static = c_dynamic = [0, 0];
                
                $.each(data.tunnels.static, function(id, tunnel) {
                    var s_color = tunnel.state === 'active' ? 'Teal' : 'Red';
                    
                    $('#static-tunnels-table > tbody').prepend([
                        '<tr>',
                            '<td><i class="zmdi zmdi-circle palette-' + s_color + '-700 text"></i> &nbsp; <a href="<?= APP::Module('Routing')->root ?>admin/tunnels/scheme/' + tunnel.hash + '" target="_blank">' + tunnel.name + '</a></td>',
                            '<td><a href="<?= APP::Module('Routing')->root ?>?filters=' + tunnel.subscribers.active[1] + '" target="_blank">' + tunnel.subscribers.active[0] + '</a></td>',
                            '<td><a href="<?= APP::Module('Routing')->root ?>?filters=' + tunnel.subscribers.total[1] + '" target="_blank">' + tunnel.subscribers.total[0] + '</a></td>',
                        '</tr>'
                    ].join(''));
                    
                    c_static[0] += parseInt(tunnel.subscribers.active[0]);
                    c_static[1] += parseInt(tunnel.subscribers.total[0]);
                });
                
                $('.static_tunnels_active_total a').html(c_static[0]);
                $('.static_tunnels_total a').html(c_static[1]);
                
                $.each(data.tunnels.dynamic, function(id, tunnel) {
                    var s_color = tunnel.state === 'active' ? 'Teal' : 'Red';
                    
                    $('#dynamic-tunnels-table > tbody').prepend([
                        '<tr>',
                            '<td><i class="zmdi zmdi-circle palette-' + s_color + '-700 text"></i> &nbsp; <a href="<?= APP::Module('Routing')->root ?>admin/tunnels/scheme/' + tunnel.hash + '" target="_blank">' + tunnel.name + '</a></td>',
                            '<td><a href="<?= APP::Module('Routing')->root ?>?filters=' + tunnel.subscribers.active[1] + '" target="_blank">' + tunnel.subscribers.active[0] + '</a></td>',
                            '<td><a href="<?= APP::Module('Routing')->root ?>?filters=' + tunnel.subscribers.total[1] + '" target="_blank">' + tunnel.subscribers.total[0] + '</a></td>',
                        '</tr>'
                    ].join(''));
                    
                    c_dynamic[0] += parseInt(tunnel.subscribers.active[0]);
                    c_dynamic[1] += parseInt(tunnel.subscribers.total[0]);
                });
                
                $('.dynamic_tunnels_active_total a').html(c_dynamic[0]);
                $('.dynamic_tunnels_total a').html(c_dynamic[1]);
            }
        });
    });
</script>