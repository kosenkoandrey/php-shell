<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Quiz</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">

        <style>
            #questions-table-header .actionBar .actions > button {
                display: none;
            }
        </style>
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Questions' => 'admin/quiz/question'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h2>Questions</h2>
                            <ul class="actions">
                                <li class="dropdown">
                                    <a href="javascript:void(0)" data-toggle="dropdown" aria-expanded="false">
                                        <i class="zmdi zmdi-more-vert"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="<?= APP::Module('Routing')->root ?>admin/quiz/question/add">Add question</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-vmiddle" id="questions-table">
                                <thead>
                                    <tr>
                                        <th data-column-id="id" data-visible="false" data-type="numeric" data-order="desc">ID</th>
                                        <th data-column-id="text" data-formatter="question">Question</th>
                                        <th data-column-id="cr_date">CrDate</th>
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
                var questions_table = $("#questions-table").bootgrid({
                    requestHandler: function (request) {
                        var model = {
                            search: request.searchPhrase,
                            current: request.current,
                            rows: request.rowCount
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
                    url: '<?= APP::Module('Routing')->root ?>admin/quiz/api/question/list.json',
                    css: {
                        icon: 'zmdi icon',
                        iconColumns: 'zmdi-view-module',
                        iconDown: 'zmdi-chevron-down pull-left',
                        iconRefresh: 'zmdi-refresh',
                        iconUp: 'zmdi-chevron-up pull-left'
                    },
                    formatters: {
                        actions: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/quiz/question/edit/' + row.id_token + '" class="btn btn-sm btn-default btn-icon waves-effect waves-circle"><span class="zmdi zmdi-edit"></span></a> ' + 
                                    '<a href="javascript:void(0)" class="btn btn-sm btn-default btn-icon waves-effect waves-circle remove-question" data-question-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></a>';
                        },
                        question: function(column, row) {
                            return  '<a href="<?= APP::Module('Routing')->root ?>admin/quiz/answer/' + row.id_token + '">'+row.text+'</a> ';
                        }
                    }
                }).on('loaded.rs.jquery.bootgrid', function () {
                    questions_table.find('.remove-question').on('click', function (e) {
                        var question_id = $(this).data('question-id');

                        swal({
                            title: 'Are you sure?',
                            text: 'You will not be able to recover this question',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            closeOnConfirm: false,
                            closeOnCancel: true
                        }, function(isConfirm){
                            if (isConfirm) {
                                $.post('<?= APP::Module('Routing')->root ?>admin/quiz/api/question/remove.json', {
                                    id: question_id
                                }, function() { 
                                    questions_table.bootgrid('reload', true);
                                    swal('Deleted!', 'Question has been deleted', 'success');
                                });
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>