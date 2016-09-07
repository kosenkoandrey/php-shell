<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Cron</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">

        <style>
            #jobs-table-header .actionBar .actions > button {
                display: none;
            }
        </style>
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Cron' => 'admin/cron',
            $data['ssh'][2] . '@' . $data['ssh'][0] .':' . $data['ssh'][1] => 'admin/cron/jobs/' . APP::Module('Routing')->get['ssh_id_hash']
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="add-cronjob" class="form-horizontal" role="form">
                            <input type="hidden" name="ssh_id_hash" value="<?= APP::Module('Routing')->get['ssh_id_hash'] ?>">
                            
                            <div class="card-header">
                                <h2>Add job</h2>
                            </div>
                            
                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="job_0" class="col-sm-2 control-label">Minute</label>
                                    <div class="col-sm-2">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="job[0]" id="job_0" value="*">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="job_1" class="col-sm-2 control-label">Hour</label>
                                    <div class="col-sm-2">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="job[1]" id="job_1" value="*">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="job_2" class="col-sm-2 control-label">Day of month</label>
                                    <div class="col-sm-2">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="job[2]" id="job_2" value="*">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="job_3" class="col-sm-2 control-label">Month</label>
                                    <div class="col-sm-2">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="job[3]" id="job_3" value="*">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="job_4" class="col-sm-2 control-label">Day of week</label>
                                    <div class="col-sm-2">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="job[4]" id="job_4" value="*">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="job_5" class="col-sm-2 control-label">CMD</label>
                                    <div class="col-sm-4">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="job[5]" id="job_5">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-2">
                                        <button type="submit" class="btn palette-Teal bg waves-effect btn-lg">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                $('#add-cronjob').submit(function(event) {
                    event.preventDefault();

                    var job_0 = $(this).find('#job_0');
                    var job_1 = $(this).find('#job_1');
                    var job_2 = $(this).find('#job_2');
                    var job_3 = $(this).find('#job_3');
                    var job_4 = $(this).find('#job_4');
                    var job_5 = $(this).find('#job_5');
                    
                    job_0.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    job_1.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    job_2.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    job_3.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    job_4.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    job_5.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    
                    if (job_0.val() === '') { job_0.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (job_1.val() === '') { job_1.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (job_2.val() === '') { job_2.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (job_3.val() === '') { job_3.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (job_4.val() === '') { job_4.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (job_5.val() === '') { job_5.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-4').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    
                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/cron/api/jobs/add.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Cronjob "' + job_0.val() + ' ' + job_1.val() + ' ' + job_2.val() + ' ' + job_3.val() + ' ' + job_4.val() + ' ' + job_5.val() + '" has been added',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: true
                                    }, function(){
                                        window.location.href = '<?= APP::Module('Routing')->root ?>admin/cron/jobs/<?= APP::Module('Routing')->get['ssh_id_hash'] ?>';
                                    });
                                    break;
                                case 'error': 
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {
                                            case 1: $('#job_0').closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); break;
                                            case 2: $('#job_1').closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); break;
                                            case 3: $('#job_2').closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); break;
                                            case 4: $('#job_3').closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); break;
                                            case 5: $('#job_4').closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); break;
                                            case 6: $('#job_5').closest('.form-group').addClass('has-error has-feedback').find('.col-sm-4').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); break;
                                            case 7: 
                                                swal({
                                                    title: 'Error!',
                                                    text: 'Command already exists',
                                                    type: 'error',
                                                    showCancelButton: false,
                                                    confirmButtonText: 'Ok',
                                                    closeOnConfirm: false
                                                }); 
                                                break;
                                        }
                                    });
                                    break;
                            }

                            $('#add-cronjob').find('[type="submit"]').html('Add').attr('disabled', false);
                        }
                    });
                });
            });
        </script>
    </body>
</html>