<script>
    $(document).on('click', '#tab-nav-<?= $data['hash'] ?> > a, #refresh-tunnels',function() {
        $('#tunnels-list').html('<div class="text-center"><div class="preloader pl-xxl"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20" /></svg></div></div>');
        
        $.ajax({
            url: '<?= APP::Module('Routing')->root ?>admin/tunnels/api/dashboard.json',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                $('#tunnels-list').html([
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
                                    '<td></td>',
                                    '<td>0</td>',
                                    '<td>0</td>',
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
                                    '<td></td>',
                                    '<td>0</td>',
                                    '<td>0</td>',
                                '</tr>',
                            '</tbody>',
                        '</table>',
                    '</div>'
                ].join(''));
                
                var c_static = c_dynamic = [0, 0];
                
                $.each(data.static, function(id, tunnel) {
                    var s_color = tunnel.state === 'active' ? 'Teal' : 'Red';
                    
                    $('#static-tunnels-table > tbody').prepend([
                        '<tr>',
                            '<td><i class="zmdi zmdi-circle palette-' + s_color + '-700 text"></i> &nbsp; <a href="<?= APP::Module('Routing')->root ?>admin/tunnels/scheme/' + tunnel.hash + '" target="_blank">' + tunnel.name + '</a></td>',
                            '<td><a href="<?= APP::Module('Routing')->root ?>?filters=' + tunnel.subscribers.active[1] + '" target="_blank">' + tunnel.subscribers.active[0] + '</a></td>',
                            '<td><a href="<?= APP::Module('Routing')->root ?>?filters=' + tunnel.subscribers.total[1] + '" target="_blank">' + tunnel.subscribers.total[0] + '</a></td>',
                        '</tr>'
                    ].join(''));
                    
                    c_static[0] += tunnel.subscribers.active[0];
                    c_static[1] += tunnel.subscribers.total[0];
                });
                
                $.each(data.dynamic, function(id, tunnel) {
                    var s_color = tunnel.state === 'active' ? 'Teal' : 'Red';
                    
                    $('#dynamic-tunnels-table > tbody').prepend([
                        '<tr>',
                            '<td><i class="zmdi zmdi-circle palette-' + s_color + '-700 text"></i> &nbsp; <a href="<?= APP::Module('Routing')->root ?>admin/tunnels/scheme/' + tunnel.hash + '" target="_blank">' + tunnel.name + '</a></td>',
                            '<td><a href="<?= APP::Module('Routing')->root ?>?filters=' + tunnel.subscribers.active[1] + '" target="_blank">' + tunnel.subscribers.active[0] + '</a></td>',
                            '<td><a href="<?= APP::Module('Routing')->root ?>?filters=' + tunnel.subscribers.total[1] + '" target="_blank">' + tunnel.subscribers.total[0] + '</a></td>',
                        '</tr>'
                    ].join(''));
                    
                    c_dynamic[0] += tunnel.subscribers.active[0];
                    c_dynamic[1] += tunnel.subscribers.total[0];
                });
            }
        });
    });
</script>