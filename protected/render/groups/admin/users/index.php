<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Пользователи в группе</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">

        <style>
            #users-table-header .actionBar .actions > button {
                display: none;
            }
        </style>
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Группы' => 'admin/groups'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h2>Пользователи в группе</h2>
                            <ul class="actions">
                                <li class="dropdown">
                                    <a href="javascript:void(0)" data-toggle="dropdown" aria-expanded="false">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/groups/users/add/<?= APP::Module('Routing')->get['group_id_hash'] ?>">Добавить пользователя</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="group_id" name="id" value="<?= APP::Module('Routing')->get['group_id_hash'] ?>">
                            <table class="table table-hover table-vmiddle" id="users-table">
                                <thead>
                                    <tr>
                                        <th data-column-id="id" data-visible="false" data-type="numeric" data-order="desc">ID</th>
                                        <th data-column-id="user_id" data-formatter="user">Пользователь</th>
                                        <th data-column-id="cr_date">Дата добавления</th>
                                        <th data-column-id="actions" data-formatter="actions">Действия</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <? APP::Render('admin/widgets/footer') ?>
        </section>

        <? APP::Render('core/widgets/page_loader') ?>
        <? APP::Render('core/widgets/ie_warning') ?>

        <!-- Javascript Libraries -->
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>

        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                var users_table = $("#users-table").bootgrid({
                    requestHandler: function (request) {
                        var model = {
                            search: request.searchPhrase,
                            current: request.current,
                            rows: request.rowCount,
                            group_id: $('#group_id').val()
                        };

                        for (var key in request.sort) {
                            model.sort_by = key;
                            model.sort_direction = request.sort[key];
                        }

                        return model;
                    },
                    ajax: true,
                    ajaxSettings: {
                        method: 'POST',
                        cache: false
                    },
                    url: '<?= APP::Module('Routing')->root ?>admin/groups/api/users/list.json',
                    css: {
                        icon: 'zmdi icon',
                        iconColumns: 'zmdi-view-module',
                        iconDown: 'zmdi-chevron-down pull-left',
                        iconRefresh: 'zmdi-refresh',
                        iconUp: 'zmdi-chevron-up pull-left'
                    },
                    formatters: {
                        actions: function(column, row) {
                            return '<a href="javascript:void(0)" class="btn btn-sm btn-default btn-icon waves-effect waves-circle remove-user" data-user-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></a>';
                        },
                        user: function(column, row) {
                            return '<a target="_blank" href="<?= APP::Module('Routing')->root ?>admin/users/profile/' + row.user_id + '">' + row.email + '</a> ';
                        }
                    }
                }).on('loaded.rs.jquery.bootgrid', function () {
                    users_table.find('.remove-user').on('click', function (e) {
                        var user_id = $(this).data('user-id');

                        swal({
                            title: 'Вы уверены?',
                            text: 'После удаления пользователя восстановить его будет невозможно',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            closeOnConfirm: false,
                            closeOnCancel: true
                        }, function(isConfirm){
                            if (isConfirm) {
                                $.post('<?= APP::Module('Routing')->root ?>admin/groups/api/users/remove.json', {
                                    id: user_id
                                }, function() { 
                                    users_table.bootgrid('reload', true);
                                    swal('Готово!', 'Пользователь был успешно удален', 'success');
                                });
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>