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
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/chosen/chosen.min.css" rel="stylesheet">

        <? APP::Render('core/widgets/css') ?>
        <style>
            .file-block{
                display: inline-block;
                margin-right:20px;
                margin-bottom:20px;
            }
            .file-block .remove-block{
                text-align: center;
                margin-top:10px;
            }
        </style>
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
                        <form id="edit-comment" class="form-horizontal" role="form" enctype="multipart/form-data">
                            <input type="hidden" name="comment" value="<?= APP::Module('Routing')->get['message_id_hash'] ?>">
                            
                            <div class="card-header">
                                <h2>Edit comment</h2>
                            </div>

                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="message" class="col-sm-2 control-label">Message</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <textarea name="message" id="message" class="form-control" placeholder="Write you comment"><?= $data['comment']['message'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <? foreach($data['files'] as $file){ 
                                        switch ($file['type']) {
                                            case 'video/mp4':
                                                ?>
                                                <div class="file-block">
                                                    <video width="150" height="100" controls>
                                                        <source src="<?= APP::Module('Routing')->root ?>comments/download/<?= APP::Module('Crypt')->Encode($file['id']) ?>" type="video/mp4">
                                                    </video>
                                                    <div class="remove-block"><a href="javascript:void(0)" class="remove-file" data-id="<?= APP::Module('Crypt')->Encode($file['id']) ?>">Remove</a></div>
                                                </div>
                                                <?
                                                break;
                                            case 'application/pdf':
                                                ?>Preview is not supported<?
                                                break;
                                            case 'image/jpeg':
                                            case 'image/png':
                                                ?><div class="file-block"><img style="height:100px;" src="<?= APP::Module('Routing')->root ?>comments/download/<?= APP::Module('Crypt')->Encode($file['id']) ?>"><div class="remove-block"><a href="javascript:void(0)" class="remove-file" data-id="<?= APP::Module('Crypt')->Encode($file['id']) ?>">Remove</a></div></div><?
                                                break;
                                        }
                                     } ?>
                                </div>
                                <div class="fg-line m-b-15">
                                    <div id="new-files"></div>
                                    <a href="javascript:void(0)" id="add-file" class="btn btn-default btn-sm">Add file</a>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/chosen/chosen.jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/autosize/dist/autosize.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/fileinput/fileinput.min.js"></script>
        
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                autosize($('#message'));
                
                $('#edit-comment #add-file').on('click', function() {
                    $('#new-files').append('<div class="form-group"><div class="file"><div class="col-sm-12"><div class="fileinput fileinput-new" data-provides="fileinput"><span class="btn btn-default btn-sm waves-effect btn-file m-r-10"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="file[]"></span><span class="fileinput-filename"></span><a href="#" class="close remove" data-dismiss="fileinput">&times;</a></div></div></div></div>');
                });

                $(document).on('click', '#edit-comment .file .remove', function(event) {
                    $(this).closest('.form-group').remove();
                });
                                
                $(document).on('click', '.remove-file', function(){
                    var $this = $(this);
                    $.post('<?= APP::Module('Routing')->root ?>admin/comments/api/file/remove.json', {'id':$this.data('id')}, function(result){
                        if(result.result == 'success'){
                            swal({
                                title: 'Done!',
                                text: 'File has been remove',
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'Ok',
                                closeOnConfirm: true
                            }, function(){
                                $this.closest('.file-block').remove();
                            });
                        }
                    });
                });

                $('#edit-comment').submit(function(event) {
                    event.preventDefault();

                    var message = $(this).find('#message');
                    message.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    if (message.val() === '') { message.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);
                    var data = new FormData($(this).get(0));
                    
                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/comments/api/update.json',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Comment has been added',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: false
                                    }, function(){
                                        window.location.href = '<?= APP::Module('Routing')->root ?>admin/comments';
                                    });
                                    break;
                                case 'error': 
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {
                                            case 2: $('#message').addClass('has-error').nextAll('.error').eq(0).addClass('is-visible').html('Not specified'); break;
                                            case 3: 
                                                swal({
                                                    title: 'Error!',
                                                    text: 'The wrong type of file "' + $('.fileinput-filename').text() + '"',
                                                    type: 'error',
                                                    showCancelButton: false,
                                                    confirmButtonText: 'Ok',
                                                    closeOnConfirm: false
                                                });
                                                return false;
                                                break;
                                        }
                                    });
                                    break;
                            }

                            $('#edit-comment').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
</html>