<?
$nav = [];

foreach ($data['path'] as $key => $value) {
    $nav[$key ? $value : 'Files'] = 'admin/files/file/' . APP::Module('Crypt')->Encode($key);
}
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Files</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">

        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/lib/codemirror.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/addon/display/fullscreen.css" rel="stylesheet">

        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? APP::Render('admin/widgets/header', 'include', $nav) ?>

        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="edit-file" class="form-horizontal" role="form" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= APP::Module('Routing')->get['file_id_hash'] ?>">

                            <div class="card-header">
                                <h2>Edit file</h2>
                            </div>

                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="title" class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="title" id="title">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="file" class="col-sm-2 control-label">File</label>
                                    <div class="col-sm-10">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn palette-Teal bg waves-effect btn-file m-r-10">
                                                <span class="fileinput-new">Select</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" id="file" value="Choose a file" name="file">
                                            </span>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <div id="upload-demo"></div>
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

        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/lib/codemirror.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/addon/edit/matchbrackets.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/addon/display/fullscreen.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/mode/htmlmixed/htmlmixed.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/mode/xml/xml.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/mode/javascript/javascript.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/mode/css/css.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/mode/clike/clike.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/codemirror/mode/php/php.js"></script>

        <? APP::Render('core/widgets/js') ?>

        <script>
            $(document).ready(function() {
                $('#title').val('<?= $data['file']['title'] ?>');
                $('.fileinput-filename').html('<?= $data['file']['id'].'.'.$data['file']['type'] ?>');

                autosize($('#html'));

                $('#edit-file').submit(function(event) {
                    event.preventDefault();

                    var title = $(this).find('#title');
                    var file = $(this).find('#file');
                    var data = new FormData($(this).get(0));

                    title.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (title.val() === '') { title.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/files/api/file/update.json',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'File "' + title.val() + '" has been updated',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: false
                                    }, function(){
                                        window.location.href = '<?= APP::Module('Routing')->root ?>admin/files/file/<?= APP::Module('Crypt')->Encode($data['group_sub_id']) ?>';
                                    });
                                    break;
                                case 'error':
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {
																																												case 1 :

																																																break;
																																												case 2 :
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

                            $('#edit-file').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
</html>