<?
$nav = [];

foreach ($data['path'] as $key => $value) {
    $nav[$key ? $value : 'Letters'] = 'admin/mail/letters/' . APP::Module('Crypt')->Encode($key);
}
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Letters</title>

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
        <? APP::Render('admin/widgets/header', 'include', $nav) ?>
        
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="edit-letter" class="form-horizontal" role="form">
                            <input type="hidden" name="id" value="<?= APP::Module('Routing')->get['letter_id_hash'] ?>">
                            
                            <div class="card-header">
                                <h2>Edit letter</h2>
                            </div>

                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="sender" class="col-sm-2 control-label">Sender</label>
                                    <div class="col-sm-5">
                                        <div class="fg-line">
                                            <select id="sender" name="sender" class="selectpicker">
                                                <? foreach ($data['senders'] as $value) { ?><option value="<?= $value['id'] ?>"><?= $value['name'] ?> &lt;<?= $value['email'] ?>&gt;</option><? } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="subject" class="col-sm-2 control-label">Subject</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="subject" id="subject">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="html" class="col-sm-2 control-label">HTML-version</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <textarea name="html" id="html" class="form-control" placeholder="Write HTML version of the letter"><?= $data['letter']['html'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="plaintext" class="col-sm-2 control-label">Plaintext-version</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <textarea name="plaintext" id="plaintext" class="form-control" placeholder="Write plaintext version of the letter"><?= $data['letter']['plaintext'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="transport" class="col-sm-2 control-label">Transport</label>
                                    <div class="col-sm-2">
                                        <div class="fg-line">
                                            <select id="transport" name="transport" class="selectpicker">
                                                <? foreach ($data['transport'] as $value) { ?><option value="<?= $value['id'] ?>"><?= $value['module'] ?> / <?= $value['method'] ?></option><? } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="priority" class="col-sm-2 control-label">Priority</label>
                                    <div class="col-sm-2">
                                        <select id="priority" name="priority" class="selectpicker">
                                            <option value="1">low</option>
                                            <option value="50">medium</option>
                                            <option value="100">maximum</option>
                                        </select>
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
                $('#sender').val('<?= $data['letter']['sender'] ?>');
                $('#subject').val('<?= $data['letter']['subject'] ?>');
                $('#transport').val('<?= $data['letter']['transport'] ?>');
                $('#priority').val('<?= $data['letter']['priority'] ?>');

                autosize($('#html'));
                autosize($('#plaintext'));
                
                $('#edit-letter').submit(function(event) {
                    event.preventDefault();

                    var sender = $(this).find('#sender');
                    var subject = $(this).find('#subject');
                    var html = $(this).find('#html');
                    var plaintext = $(this).find('#plaintext');
                    var transport = $(this).find('#transport');
                    var priority = $(this).find('#priority');
 
                    sender.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    html.closest('.form-group').removeClass('has-error has-feedback');
                    plaintext.closest('.form-group').removeClass('has-error has-feedback');
                    subject.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    transport.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    priority.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (sender.val() === '') { sender.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-5').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (subject.val() === '') { subject.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (html.val() === '') { 
                        html.closest('.form-group').addClass('has-error has-feedback'); 
                        swal({
                            title: 'Error!',
                            text: 'HTML-version empty',
                            type: 'error',
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                            closeOnConfirm: false
                        }); 
                        return false; 
                    }
                    if (plaintext.val() === '') { 
                        plaintext.closest('.form-group').addClass('has-error has-feedback'); 
                        swal({
                            title: 'Error!',
                            text: 'Plaintext-version empty',
                            type: 'error',
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                            closeOnConfirm: false
                        }); 
                        return false; 
                    }
                    if (transport.val() === '') { transport.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (priority.val() === '') { priority.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/mail/api/letters/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Letter "' + subject.val() + '" has been updated',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: false
                                    }, function(){
                                        window.location.href = '<?= APP::Module('Routing')->root ?>admin/mail/letters/<?= APP::Module('Crypt')->Encode($data['group_sub_id']) ?>';
                                    });
                                    break;
                                case 'error': 
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {}
                                    });
                                    break;
                            }

                            $('#edit-letter').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
</html>