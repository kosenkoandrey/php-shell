<script>
    $(document).on('click', '#tab-nav-<?= $data['hash'] ?> > a', function () {
        GetAllUsers();
    });
    
    function GetAllUsers() {
        $('#all-users-list').html('<div class="text-center"><div class="preloader pl-xxl"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20" /></svg></div></div>');
        
        $.ajax({
            url: '<?= APP::Module('Routing')->root ?>admin/users/api/dashboard/all.json',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                $('#all-users-list').html([
                    '<div class="table-responsive ь">',
                        '<table id="roles-users-list-table" class="table table-hover">',
                            '<thead>',
                                '<tr>',
                                    '<th width="35%">Роль</th>',
                                    '<th width="65%">Пользователи</th>',
                                '</tr>',
                            '</thead>',
                            '<tbody>',
                                '<tr>',
                                    '<td>Активированные</td>',
                                    '<td><a href="#">' + data.roles.user + '</a></td>',
                                '</tr>',
                                '<tr>',
                                    '<td>Ожидают активации</td>',
                                    '<td><a href="#">' + data.roles.new + '</a></td>',
                                '</tr>',
                                '<tr>',
                                    '<td>Администраторы</td>',
                                    '<td><a href="#">' + data.roles.admin + '</a></td>',
                                '</tr>',
                            '</tbody>',
                        '</table>',
                        '<table id="states-users-list-table" class="table table-hover">',
                            '<thead>',
                                '<tr>',
                                    '<th width="35%">Состояние</th>',
                                    '<th width="65%">Пользователи</th>',
                                '</tr>',
                            '</thead>',
                            '<tbody>',
                                '<tr>',
                                    '<td>Активные</td>',
                                    '<td><a href="#">' + data.states.active + '</a></td>',
                                '</tr>',
                                '<tr>',
                                    '<td>Не активные</td>',
                                    '<td><a href="#">' + data.states.inactive + '</a></td>',
                                '</tr>',
                                '<tr>',
                                    '<td>В черном списке</td>',
                                    '<td><a href="#">' + data.states.blacklist + '</a></td>',
                                '</tr>',
                                '<tr>',
                                    '<td>Невозможно доставить почту</td>',
                                    '<td><a href="#">' + data.states.dropped + '</a></td>',
                                '</tr>',
                            '</tbody>',
                        '</table>',
                    '</div>'
                ].join(''));
            }
        });
    }
</script>