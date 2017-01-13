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
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">

        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Shortcodes' => 'admin/mail/shortcodes',
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="update-shortcode" class="form-horizontal" role="form">
                            <input type="hidden" name="shortcode_id" value="<?= APP::Module('Routing')->get['shortcode_id_hash'] ?>">
                            
                            <div class="card-header">
                                <h2>Edit shortcode</h2>
                            </div>

                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="code" class="col-sm-2 control-label">Code</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="code" id="code">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="value" class="col-sm-2 control-label">Value</label>
                                    <div class="col-sm-6">
                                        <div class="fg-line">
                                            <textarea id="value" name="value" class="form-control"><?= $data['content'] ?></textarea>
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

        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                $('#code').val('<?= $data['code'] ?>');
                autosize($('#value'));

                $('#update-shortcode').submit(function(event) {
                    event.preventDefault();

                    var code = $(this).find('#code');
                    var value = $(this).find('#value');
                    
                    code.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    value.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (code.val() === '') { code.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (value.val() === '') { value.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-6').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/mail/api/shortcodes/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Shortcode "' + code.val() + '" has been updates',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: false
                                    }, function(){
                                        window.location.href = '<?= APP::Module('Routing')->root ?>admin/mail/shortcodes';
                                    });
                                    break;
                                case 'error': 
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {
                                            case 1: $('#code').closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); break;
                                            case 2: $('#content').closest('.form-group').addClass('has-error has-feedback').find('.col-sm-6').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); break;
                                        }
                                    });
                                    break;
                            }

                            $('#update-shortcode').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                });
            });
        </script>
    </body>
</html>