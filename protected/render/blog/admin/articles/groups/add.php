<?
$nav = [];

foreach ($data['path'] as $key => $value) {
    $nav[$key ? $value : 'Articles'] = 'admin/blog/articles/' . APP::Module('Crypt')->Encode($key);
}
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Articles</title>

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
                        <form id="add-group" class="form-horizontal" role="form" enctype="multipart/form-data">
                            <input type="hidden" name="sub_id" value="<?= APP::Module('Crypt')->Encode($data['group_sub_id']) ?>">
                            
                            <div class="card-header">
                                <h2>Add article group</h2>
                            </div>
                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="uri" class="col-sm-2 control-label">URI</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="uri" id="uri">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="page_title" class="col-sm-2 control-label">Page title</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="page_title" id="page_title">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="h1_title" class="col-sm-2 control-label">H1 title</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="h1_title" id="h1_title">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="annotation" class="col-sm-2 control-label">Annotation</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <textarea name="annotation" id="annotation" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Image (1920x500)</label>
                                    <div class="col-sm-10">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn palette-Teal bg waves-effect btn-file m-r-10">
                                                <span class="fileinput-new">Select</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" id="image" name="image" value="Choose a file" accept="image/*">
                                            </span>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <textarea name="description" id="description" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="keywords" class="col-sm-2 control-label">Keywords</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="keywords" id="keywords">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="robots" class="col-sm-2 control-label">Robots</label>
                                    <div class="col-sm-2">
                                        <select id="robots" name="robots" class="selectpicker">
                                            <option value="all" selected>all</option>
                                            <option value="none">none</option>
                                            <option value="index,follow">index,follow</option>
                                            <option value="noindex,follow">noindex,follow</option>
                                            <option value="index,nofollow">index,nofollow</option>
                                            <option value="noindex,nofollow">noindex,nofollow</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-5">
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/autosize/dist/autosize.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/fileinput/fileinput.min.js"></script>
        
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                autosize($('#annotation'));
                autosize($('#description'));
                
                $('#add-group').submit(function(event) {
                    event.preventDefault();

                    var uri = $(this).find('#uri');
                    var page_title = $(this).find('#page_title');
                    var h1_title = $(this).find('#h1_title');
                    var annotation = $(this).find('#annotation');
                    var description = $(this).find('#description');
                    var keywords = $(this).find('#keywords');
                    var robots = $(this).find('#robots');
                    
                    uri.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    page_title.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    h1_title.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    annotation.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    description.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    keywords.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    robots.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    
                    if (uri.val() === '') { uri.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (page_title.val() === '') { page_title.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (h1_title.val() === '') { h1_title.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (annotation.val() === '') { annotation.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (description.val() === '') { description.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (keywords.val() === '') { keywords.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (robots.val() === '') { robots.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    
                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);
                    var form_data = new FormData($(this)[0]);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/blog/api/articles/groups/add.json',
                        data: form_data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Group "' + page_title.val() + '" has been added',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: false
                                    }, function(){
                                        window.location.href = '<?= APP::Module('Routing')->root ?>admin/blog/articles/<?= APP::Module('Crypt')->Encode($data['group_sub_id']) ?>';
                                    });
                                    break;
                                case 'error': 
                                    $.each(result.errors, function(i, error) {
                                        switch(error) {}
                                    });
                                    break;
                            }

                            $('#add-group').find('[type="submit"]').html('Add').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
</html>