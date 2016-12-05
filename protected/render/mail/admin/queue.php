<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Mail</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">

        <style>
            #queue-table-header .actionBar .actions > button {
                display: none;
            }
        </style>
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Mail queue' => 'admin/mail/queue',
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h2>Queue</h2>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-vmiddle" id="queue-table">
                                <thead>
                                    <tr>
                                        <th data-column-id="id" data-visible="false" data-type="numeric" data-order="desc">ID</th>
                                        <th data-column-id="email" data-formatter="user">User</th>
                                        <th data-column-id="letter" data-formatter="letter">Letter</th>
                                        <th data-column-id="copies" data-formatter="copies" data-css-class="text-uppercase">Copies</th>
                                        <th data-column-id="sender" data-formatter="sender">Sender</th>
                                        <th data-column-id="transport" data-formatter="transport">Transport</th>
                                        <th data-column-id="result" data-visible="false">Result</th>
                                        <th data-column-id="retries" data-visible="false">Retries</th>
                                        <th data-column-id="ping" data-visible="false">Ping</th>
                                        <th data-column-id="priority">Priority</th>
                                        <th data-column-id="token" data-visible="false">Token</th>
                                        <th data-column-id="execute">Execute</th>
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
                var queue_table = $("#queue-table").bootgrid({
                    ajax: true,
                    ajaxSettings: {
                        method: 'POST',
                        cache: false
                    },
                    url: '<?= APP::Module('Routing')->root ?>admin/mail/api/queue/list.json',
                    css: {
                        icon: 'zmdi icon',
                        iconColumns: 'zmdi-view-module',
                        iconDown: 'zmdi-chevron-down pull-left',
                        iconRefresh: 'zmdi-refresh',
                        iconUp: 'zmdi-chevron-up pull-left'
                    },
                    formatters: {
                        user: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/users/edit/' + row.user_token + '" target="_blank">' + row.recepient + '</a>';
                        },
                        letter: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/mail/letters/' + row.letter_group[1] + '/edit/' + row.letter[1] + '" target="_blank">' + row.subject + '</a>';
                        },
                        copies: function(column, row) {
                            return  parseInt(row.copies) ? '<div class="btn-group btn-group-xs" role="group"><a href="<?= APP::Module('Routing')->root ?>mail/html/' + row.log_token + '" target="_blank" class="btn btn-default waves-effect">HTML</a><a href="<?= APP::Module('Routing')->root ?>mail/plaintext/' + row.log_token + '" target="_blank" class="btn btn-default waves-effect">PLAINTEXT</a></div>' : 'none';
                        },
                        sender: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/mail/senders/' + row.sender_group[1] + '/edit/' + row.sender[1] + '" target="_blank">' + row.sender_name + ' (' + row.sender_email + ')</a>';
                        },
                        transport: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>' + row.transport_settings + '" target="_blank">' + row.transport_module + ' / ' + row.transport_method + '</a>';
                        },
                        actions: function(column, row) {
                            return  '<a href="javascript:void(0)" class="btn btn-sm btn-default btn-icon waves-effect waves-circle remove-queue" data-queue-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></a>';
                        }
                    }
                }).on('loaded.rs.jquery.bootgrid', function () {
                    queue_table.find('.remove-queue').on('click', function (e) {
                        var queue_id = $(this).data('queue-id');

                        swal({
                            title: 'Are you sure?',
                            text: 'You will not be able to recover this entry',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            closeOnConfirm: false,
                            closeOnCancel: true
                        }, function(isConfirm){
                            if (isConfirm) {
                                $.post('<?= APP::Module('Routing')->root ?>admin/mail/api/queue/remove.json', {
                                    id: queue_id
                                }, function() { 
                                    queue_table.bootgrid('reload', true);
                                    swal('Deleted!', 'Entry has been deleted', 'success');
                                });
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>