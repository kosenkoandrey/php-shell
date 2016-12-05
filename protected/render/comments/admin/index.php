<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Comments</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">

        <style>
            #comments-table-header .actionBar .actions > button {
                display: none;
            }
        </style>
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Comments' => 'admin/comments'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h2>Comments</h2>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-vmiddle" id="comments-table">
                                <thead>
                                    <tr>
                                        <th data-column-id="id" data-visible="false" data-type="numeric" data-order="desc">ID</th>
                                        <th data-column-id="answer" data-formatter="answer">Answer</th>
                                        <th data-column-id="user" data-formatter="user">User</th>
                                        <th data-column-id="object" data-formatter="object">Object</th>
                                        <th data-column-id="message">Message</th>
                                        <th data-column-id="up_date">UpDate</th>
                                        <th data-column-id="actions" data-formatter="actions">Actions</th>
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
                var comments_table = $("#comments-table").bootgrid({
                    ajax: true,
                    ajaxSettings: {
                        method: 'POST',
                        cache: false
                    },
                    url: '<?= APP::Module('Routing')->root ?>admin/comments/api/list.json',
                    css: {
                        icon: 'zmdi icon',
                        iconColumns: 'zmdi-view-module',
                        iconDown: 'zmdi-chevron-down pull-left',
                        iconRefresh: 'zmdi-refresh',
                        iconUp: 'zmdi-chevron-up pull-left'
                    },
                    formatters: {
                        answer: function(column, row) {
                            return parseInt(row.sub_id) ? 'Yes' : 'No';
                        },
                        user: function(column, row) {
                            return  row.email ? '<a href="<?= APP::Module('Routing')->root ?>admin/users/profile/' + row.user + '" target="_blank">' + row.email + '</a>' : 'Guest';
                        },
                        object: function(column, row) {
                            return '<a href="' + row.url + '#comment-' + row.id_token + '" target="_blank">' + row.object_name + ' / ' + row.object_id + '</a>';
                        },
                        actions: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/comments/edit/' + row.id_token + '" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-edit"></span></a> ' + 
                                    '<a href="javascript:void(0)" class="btn btn-sm btn-default btn-icon waves-effect waves-circle remove-comment" data-comment-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></a>';
                        }
                    }
                }).on('loaded.rs.jquery.bootgrid', function () {
                    comments_table.find('.remove-comment').on('click', function (e) {
                        var comment_id = $(this).data('comment-id');

                        swal({
                            title: 'Are you sure?',
                            text: 'You will not be able to recover this comment',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            closeOnConfirm: false,
                            closeOnCancel: true
                        }, function(isConfirm){
                            if (isConfirm) {
                                $.post('<?= APP::Module('Routing')->root ?>admin/comments/api/remove.json', {
                                    id: comment_id
                                }, function() { 
                                    comments_table.bootgrid('reload', true);
                                    swal('Deleted!', 'Comment has been deleted', 'success');
                                });
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>