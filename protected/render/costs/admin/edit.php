<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Costs</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Costs' => 'admin/costs'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="update-cost" class="form-horizontal" role="form">
                            <input type="hidden" name="id" value="<?= APP::Module('Routing')->get['cost_id_hash'] ?>">
                            
                            <div class="card-header">
                                <h2>Edit cost</h2>
                            </div>
                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="date" class="col-sm-2 control-label">Date</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="date" id="date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="amount" class="col-sm-2 control-label">Amount</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="amount" id="amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="utm_source" class="col-sm-2 control-label">UTM-source</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="utm_source" id="utm_source">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="utm_medium" class="col-sm-2 control-label">UTM-medium</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="utm_medium" id="utm_medium">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="utm_campaign" class="col-sm-2 control-label">UTM-campaign</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="utm_campaign" id="utm_campaign">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="utm_term" class="col-sm-2 control-label">UTM-term</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="utm_term" id="utm_term">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="utm_content" class="col-sm-2 control-label">UTM-content</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="utm_content" id="utm_content">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="comment" class="col-sm-2 control-label">Comment</label>
                                    <div class="col-sm-6">
                                        <div class="fg-line">
                                            <textarea name="comment" id="comment" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-5">
                                        <button type="submit" class="btn palette-Teal bg waves-effect btn-lg">Save changes</button>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/autosize/dist/autosize.min.js"></script>
        
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/moment/min/moment.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                $('#date')
                .val('<?= $data['cost']['cost_date'] ?>')
                .datetimepicker({
                    format: 'YYYY-MM-DD'
                });
                $('#amount').val('<?= $data['cost']['amount'] ?>');
                $('#utm_source').val('<?= $data['cost']['utm_source'] ?>');
                $('#utm_medium').val('<?= $data['cost']['utm_medium'] ?>');
                $('#utm_campaign').val('<?= $data['cost']['utm_campaign'] ?>');
                $('#utm_term').val('<?= $data['cost']['utm_term'] ?>');
                $('#utm_content').val('<?= $data['cost']['utm_content'] ?>');
                $('#comment').val('<?= $data['cost']['comment'] ?>');

                autosize($('#comment'));
                
                $('#update-cost').submit(function(event) {
                    event.preventDefault();

                    var date = $(this).find('#date');
                    var amount = $(this).find('#amount');
                    var utm_source = $(this).find('#utm_source');
                    var utm_medium = $(this).find('#utm_medium');
                    var utm_campaign = $(this).find('#utm_campaign');
                    var utm_term = $(this).find('#utm_term');
                    var utm_content = $(this).find('#utm_content');

                    date.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    amount.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    utm_source.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    utm_medium.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    utm_campaign.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    utm_term.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    utm_content.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (date.val() === '') { date.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (amount.val() === '') { amount.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (utm_source.val() === '') { utm_source.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (utm_medium.val() === '') { utm_medium.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (utm_campaign.val() === '') { utm_campaign.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (utm_term.val() === '') { utm_term.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (utm_content.val() === '') { utm_content.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/costs/api/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Cost "' + amount.val() + '" has been updated',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: false
                                    }, function(){
                                        window.location.href = '<?= APP::Module('Routing')->root ?>admin/costs';
                                    });
                                    break;
                                case 'error': 
                                    $.each(result.errors, function(i, error) {});
                                    break;
                            }

                            $('#update-cost').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
  </html>