<?
$filters = htmlspecialchars(isset($_GET['filters']) ? APP::Module('Crypt')->Decode($_GET['filters']) : '{"logic":"intersect","rules":[{"method":"email","settings":{"logic":"LIKE","value":"%"}}]}');
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Users</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet"> 
        <link href="<?= APP::Module('Routing')->root ?>public/modules/users/rules.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/letter-selector/style.css" rel="stylesheet">
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
            'Users' => 'admin/users'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h2>Users</h2>
                            <ul class="actions">
                                <li class="dropdown">
                                    <a href="javascript:void(0)" data-toggle="dropdown">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/users/add">Add user</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/users/roles">Roles</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/users/oauth/clients">OAuth clients</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/users/services">Services</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/users/auth">Authentication</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/users/passwords">Passwords</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/users/notifications">Notifications</a></li>
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/users/timeouts">Timeouts</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body card-padding">
                            <input type="hidden" name="search" value="<?= $filters ?>" id="search">
                            <div class="btn-group">
                                <button type="button" id="render-table" class="btn btn-default"><i class="zmdi zmdi-check"></i> Apply</button>
                            
                                <div class="btn-group">
                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button">
                                        Actions <span class="caret"></span>
                                    </button>
                                    <ul id="search_results_actions" class="dropdown-menu" role="menu">
                                        <li><a data-action="remove" href="javascript:void(0)">Remove</a></li>
                                        <li><a data-action="add_tag" href="javascript:void(0)">Add Tag</a></li>
                                        <li><a data-action="change_state" href="javascript:void(0)">Change state</a></li>
                                        <li><a data-action="send_mail" href="javascript:void(0)">Send mail</a></li>
                                        
                                        <li><a data-action="tunnel_subscribe" href="javascript:void(0)">Subscribe Tunnel</a></li>
                                        <li><a data-action="tunnel_pause" href="javascript:void(0)">Tunnel pause</a></li>
                                        <li><a data-action="tunnel_complete" href="javascript:void(0)">Tunnel complete</a></li>
                                        <li><a data-action="tunnel_manually_complete" href="javascript:void(0)">Subscribe tunnel and complete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-vmiddle" id="users-table">
                                <thead>
                                    <tr>
                                        <th data-column-id="id" data-type="numeric" data-order="desc">ID</th>
                                        <th data-column-id="email" data-formatter="email">E-Mail</th>
                                        <th data-column-id="tel">Phone</th>
                                        <th data-column-id="social" data-formatter="social">Social</th>
                                        <th data-column-id="role">Role</th>
                                        <th data-column-id="reg_date">Reg date</th>
                                        <th data-column-id="last_visit">Last visit</th>
                                        <th data-column-id="actions" data-formatter="actions" data-sortable="false">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            
            <div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <form id="user-action-form" method="post" class="form-horizontal"></form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn palette-Teal bg waves-effect" id="send_action">Done</button>
                            <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/json/dist/jquery.json.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/moment/min/moment.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/letter-selector/script.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/modules/tunnels/scheme/tunnel-selector/script.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/modules/users/rules.js"></script> 
        <? APP::Render('core/widgets/js') ?>
        <script>
            $(document).ready(function() {
                $('#search').RefRulesEditor({
                    'debug': true,
                    'url' : '<?= APP::Module('Routing')->root ?>'
                });
                
                var user_modal = {
                    build : function(action, rules){
                        var modal = $('#user-modal');
                        var form = $('#user-action-form', modal);

                        form.append(
                            [
                                "<input type='hidden' value='"+action+"' name='action' />",
                                "<input type='hidden' value='"+rules+"' name='rules' />"
                            ].join('')
                        );
                
                        switch(action){
                            case 'tunnel_subscribe' :
                                $('.modal-title', modal).html('Subscribe Tunnel');
                                form.append(
                                    [
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-4 control-label">Tunnel ID</label>',
                                            '<div class="col-sm-8">',
                                                '<div class="fg-line">',
                                                    '<input type="text" value="" name="settings[tunnel][0]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>',

                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-4 control-label">Tunnel Action</label>',
                                            '<div class="col-sm-8">',
                                                '<div class="select">',
                                                    '<select name="settings[tunnel][1]"  class="form-control">',
                                                        '<option value="actions">actions</option>',
                                                        '<option value="conditions">conditions</option>',
                                                        '<option value="timeouts">timeouts</option>',
                                                    '</select>',
                                                '</div>',
                                            '</div>',
                                        '</div>',

                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-4 control-label">Tunnel Action ID</label>',
                                            '<div class="col-sm-8">',
                                                '<div class="fg-line">',
                                                    '<input type="text" value="" name="settings[tunnel][2]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>',

                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-4 control-label">Tunnel Timeout</label>',
                                            '<div class="col-sm-8">',
                                                '<div class="fg-line">',
                                                    '<input type="text" value="" name="settings[tunnel][3]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>',
                                        
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-4 control-label">Welcome Tunnel ID</label>',
                                            '<div class="col-sm-8">',
                                                '<div class="fg-line">',
                                                    '<input type="text" value="" name="settings[welcome][0]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>',

                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-4 control-label">Welcome Tunnel Action</label>',
                                            '<div class="col-sm-8">',
                                                '<div class="select">',
                                                    '<select name="settings[welcome][1]"  class="form-control">',
                                                        '<option value="actions">actions</option>',
                                                        '<option value="conditions">conditions</option>',
                                                        '<option value="timeouts">timeouts</option>',
                                                    '</select>',
                                                '</div>',
                                            '</div>',
                                        '</div>',

                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-4 control-label">Welcome Tunnel Action ID</label>',
                                            '<div class="col-sm-8">',
                                                '<div class="fg-line">',
                                                    '<input type="text" value="" name="settings[welcome][2]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>',

                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-4 control-label">Welcome Tunnel Timeout</label>',
                                            '<div class="col-sm-8">',
                                                '<div class="fg-line">',
                                                    '<input type="text" value="" name="settings[welcome][3]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>',
                                        
                                        '<div class="panel-body">',
                                            '<div class="form-group">',
                                                '<label for="" class="col-sm-4 control-label">Activation ID</label>',
                                                '<div class="col-sm-8">',
                                                    '<div class="fg-line">',
                                                        '<input type="text" value="" name="settings[activation][0]" class="form-control" />',
                                                    '</div>',
                                                '</div>',
                                            '</div>',

                                            '<div class="form-group">',
                                                '<label for="" class="col-sm-4 control-label">Activation Url</label>',
                                                '<div class="col-sm-8">',
                                                    '<div class="fg-line">',
                                                        '<input type="text" value="" name="settings[activation][1]" class="form-control" />',
                                                    '</div>',
                                                '</div>',
                                            '</div>',

                                            '<div class="form-group">',
                                                '<label for="" class="col-sm-4 control-label">Queue Timeout</label>',
                                                '<div class="col-sm-8">',
                                                    '<div class="fg-line">',
                                                        '<input type="text" value="" name="settings[queue_timeout]" class="form-control" />',
                                                    '</div>',
                                                '</div>',
                                            '</div>',
                                        '</div>',
                                        
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-4 control-label">Source</label>',
                                            '<div class="col-sm-8">',
                                                '<div class="fg-line">',
                                                    '<input type="text" value="" name="settings[source]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>'
                                        
                                    ].join('')
                                );
                                modal.modal('show');
                                break;
                            case 'remove' :
                                var data = form.serialize();
                                user_modal.send(data);
                                break;
                            case 'add_tag' :
                                $('.modal-title', modal).html('Add Tag');
                                form.append(
                                    [
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-2 control-label">Item</label>',
                                            '<div class="col-sm-4">',
                                                '<div class="fg-line">',
                                                    '<input type="text" value="" name="settings[item]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>',
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-2 control-label">Value</label>',
                                            '<div class="col-sm-4">',
                                                '<div class="fg-line">',
                                                    '<input type="text" value="" name="settings[value]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>'
                                    ].join('')
                                );
                                modal.modal('show');
                                break;
                            case 'change_state' :
                                $('.modal-title', modal).html('Change state');
                                form.append(
                                    [
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-2 control-label">State</label>',
                                            '<div class="col-sm-4">',
                                                '<div class="select">',
                                                    '<select name="settings[value]"  class="form-control">',
                                                        '<option value="active">Активный</option>',
                                                        '<option value="inactive">Неактивный</option>',
                                                        '<option value="blacklist">В черном списке</option>',
                                                    '</select>',
                                                '</div>',
                                            '</div>',
                                        '</div>'
                                    ].join('')
                                );
                                modal.modal('show');
                                break;
                            case 'send_mail' :
                                $('.modal-title', modal).html('Send mail');
                                form.append(
                                    [
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-2 control-label">Letter</label>',
                                            '<div class="col-sm-6">',
                                                '<div class="fg-line">',
                                                    '<input type="hidden" id="in_letter" value="" name="settings[letter]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>'
                                    ].join('')
                                );
                                $('#in_letter', modal).MailingLetterSelector({'url':'<?= APP::Module('Routing')->root ?>'});
                                modal.modal('show');
                                break;
                            case 'tunnel_pause' :
                                $('.modal-title', modal).html('Tunnel pause');
                                form.append(
                                    [
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-2 control-label">Tunnel</label>',
                                            '<div class="col-sm-6">',
                                                '<div class="fg-line">',
                                                    '<input type="hidden" id="tunnel_id" value="" name="settings[tunnel_id]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>'
                                    ].join('')
                                );
                                $('#tunnel_id', modal).TunnelSelector({'url':'<?= APP::Module('Routing')->root ?>'});
                                modal.modal('show');
                                break;
                            case 'tunnel_complete' :
                                $('.modal-title', modal).html('Tunnel complete');
                                form.append(
                                    [
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-2 control-label">Tunnel</label>',
                                            '<div class="col-sm-6">',
                                                '<div class="fg-line">',
                                                    '<input type="hidden" id="tunnel_id" value="" name="settings[tunnel_id]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>'
                                    ].join('')
                                );
                                $('#tunnel_id', modal).TunnelSelector({'url':'<?= APP::Module('Routing')->root ?>'});
                                modal.modal('show');
                                break;
                            case 'tunnel_manually_complete' :
                                $('.modal-title', modal).html('Subscribe tunnel and complete');
                                form.append(
                                    [
                                        '<div class="form-group">',
                                            '<label for="" class="col-sm-2 control-label">Tunnel</label>',
                                            '<div class="col-sm-6">',
                                                '<div class="fg-line">',
                                                    '<input type="hidden" id="tunnel_id" value="" name="settings[tunnel_id]" class="form-control" />',
                                                '</div>',
                                            '</div>',
                                        '</div>'
                                    ].join('')
                                );
                                $('#tunnel_id', modal).TunnelSelector({'url':'<?= APP::Module('Routing')->root ?>'});
                                modal.modal('show');
                                break;
                        }
                        
                    },
                    send : function(data){
                        var modal = $('#user-modal');
                        $.post('<?= APP::Module('Routing')->root ?>admin/users/api/action.json', data, function(res) { 
                            if(res.status == 'success'){
                                modal.modal('hide');
                                $("#users-table").bootgrid('reload', true);
                                swal({
                                    title: 'Done!',
                                    text: 'Action has been completed',
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Ok',
                                    closeOnConfirm: false
                                });
                            }else{
                                swal('Error!', 'Action has been error', 'error');
                            }
                        });
                        return false;
                    }
                };
                
                $(document).on('click', '#render-table', function () {
                    $('#users-table').bootgrid('reload');
                });
                
                $(document).on('click', '#search_results_actions a', function () {
                    var action = $(this).data('action');
                    
                    swal({
                        title: 'Are you sure?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        closeOnConfirm: true,
                        closeOnCancel: true
                    }, function(isConfirm){
                        if (isConfirm) {
                            user_modal.build(action, $('#search').val());
                        }
                    });
                });
                
                $(document).on('click', '#send_action', function(){
                    var modal = $('#user-modal');
                    var form = $('#user-action-form', modal);
                    var data = form.serialize();
                    user_modal.send(data);
                    return false;
                });
                
                $('#user-modal').on('hide.bs.modal', function (event) {
                    $('#user-action-form', $(this)).html('');
                });

                var users_table = $("#users-table").bootgrid({
                    requestHandler: function (request) {
                        var model = {
                            search: $('#search').val(),
                            current: request.current,
                            rows: request.rowCount
                        };

                        for (var key in request.sort) {
                            model.sort_by = key;
                            model.sort_direction = request.sort[key];
                        }

                        return JSON.stringify(model);
                    },
                    ajax: true,
                    ajaxSettings: {
                        method: 'POST',
                        cache: false,
                        contentType: 'application/json'
                    },
                    url: '<?= APP::Module('Routing')->root ?>admin/users/api/search.json',
                    css: {
                        icon: 'zmdi icon',
                        iconColumns: 'zmdi-view-module',
                        iconDown: 'zmdi-chevron-down pull-left',
                        iconRefresh: 'zmdi-refresh',
                        iconUp: 'zmdi-chevron-up pull-left'
                    },
                    templates: {
                        search: ""
                    },
                    formatters: {
                        email: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/users/profile/' + row.id + '" target="_blank">' + row.email + '</a>';
                        },
                        actions: function(column, row) {
                            return  '<a target="_blank" href="<?= APP::Module('Routing')->root ?>admin/users/edit/' + row.user_id_token + '" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-edit"></span></a> ' + 
                                    '<a href="javascript:void(0)" class="btn btn-sm btn-default btn-icon waves-effect waves-circle remove-user" data-user-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></a>';
                        },
                        social: function(column, row) {
                            var html = '';
                            $.each(row.social, function(i, j){
                                switch(j.service){
                                    case 'vk' :
                                        html += '<a target="_blank" href="https://vk.com/id'+j.extra+'" class="btn btn-sm btn-default btn-icon waves-effect waves-circle">'+j.service+'</a>';
                                        break;
                                    case 'fb' :
                                        html += '<a target="_blank" href="http://facebook.com/profile.php?id='+j.extra+'" class="btn btn-sm btn-default btn-icon waves-effect waves-circle">'+j.service+'</a>';
                                        break;
                                }
                            });
                            return html;
                        }
                    }
                }).on('loaded.rs.jquery.bootgrid', function () {
                    users_table.find('.remove-user').on('click', function (e) {
                        var user_id = $(this).data('user-id');

                        swal({
                            title: 'Are you sure?',
                            text: 'You will not be able to recover this user',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            closeOnConfirm: false,
                            closeOnCancel: true
                        }, function(isConfirm){
                            if (isConfirm) {
                                $.post('<?= APP::Module('Routing')->root ?>admin/users/api/remove.json', {
                                    id: user_id
                                }, function() { 
                                    users_table.bootgrid('reload', true);
                                    swal('Deleted!', 'User has been deleted', 'success');
                                });
                            }
                        });
                    });
                });
            });
        </script>
    </body>
  </html>