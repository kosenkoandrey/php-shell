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
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/croppie/croppie.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/summernote/dist/summernote.css" rel="stylesheet">

        <style>
            .croppie-container {
                padding: 0;
            }
        </style>
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? APP::Render('admin/widgets/header', 'include', $nav) ?>
        
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="add-article" class="form-horizontal" role="form">
                            <input type="hidden" name="group_id" value="<?= APP::Module('Crypt')->Encode($data['group_sub_id']) ?>">
                            
                            <div class="card-header">
                                <h2>Add article</h2>
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
                                    <label class="col-sm-2 control-label">Content</label>
                                    <div class="col-sm-10">
                                        <div id="html-content-editor"></div>
                                        <textarea name="html_content" id="html_content" class="form-control" style="display: none"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Image</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" class="form-control" name="image" id="image">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn palette-Teal bg waves-effect btn-file m-r-10">
                                                <span class="fileinput-new">Select</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" id="upload-image" value="Choose a file" accept="image/*">
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
                                    <label for="tags" class="col-sm-2 control-label">Tags</label>
                                    <div class="col-sm-10">
                                        <select name="tags[]" id="tags" data-placeholder="Set tags..." multiple style="width: 100%"></select>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/croppie/croppie.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/summernote/dist/summernote-updated.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/fileinput/fileinput.min.js"></script>

        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                autosize($('#annotation'));
                autosize($('#description'));
                
                $('#html-content-editor').summernote({
                    onChange: function(contents, $editable) {
                        $('#html_content').val(contents);
                    },
                    height: 300
                });

                var $image_crop;

		$image_crop = $('#upload-demo').croppie({
                    viewport: {
                        width: 848,
                        height: 535
                    },
                    boundary: {
                        width: '100%',
                        height: 650
                    },
                    enableExif: true
		});
                
                $('#tags').select2({
                    tags: true,
                    placeholder: 'Select tags...',
                    ajax: {
                        url: '<?= APP::Module('Routing')->root ?>admin/blog/api/articles/tags.json',
                        dataType: 'json',
                        delay: 250,
                        id: function(obj){ return obj.name; },
                        data: function (params) {
                            return {
                                q: params.term,
                            };
                        },
                        processResults: function (data, page) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; },
                    minimumInputLength: 3,
                    maximumSelectionLength: 8
                });

		$('#upload-image').on('change', function() { 
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
	            
                        reader.onload = function(e) {
                            $('#image-demo').addClass('ready');
                            $image_crop.croppie('bind', { url: e.target.result });
                        };
                        
                        reader.readAsDataURL(this.files[0]);
                    } else {
		        swal('Sorry - you\'re browser doesn\'t support the FileReader API');
		    }
                });

                $('#add-article').submit(function(event) {
                    var form = this;
                    event.preventDefault();
                    
                    $image_crop.croppie('result', {
                        type: 'canvas',
			size: 'viewport'
                    }).then(function(b64image) {
                        $('#image').val(b64image);
                        
                        var uri = $(form).find('#uri');
                        var page_title = $(form).find('#page_title');
                        var h1_title = $(form).find('#h1_title');
                        var annotation = $(form).find('#annotation');
                        var html_content = $(form).find('#html_content');
                        var description = $(form).find('#description');
                        var keywords = $(form).find('#keywords');
                        var robots = $(form).find('#robots');

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
                        if (html_content.val() === '') { swal('Content not specified'); return false; }
                        if (description.val() === '') { description.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                        if (keywords.val() === '') { keywords.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                        if (robots.val() === '') { robots.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-2').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                        $(form).find('[type="submit"]').html('Processing...').attr('disabled', true);

                        $.ajax({
                            type: 'post',
                            url: '<?= APP::Module('Routing')->root ?>admin/blog/api/articles/add.json',
                            data: $(form).serialize(),
                            success: function(result) {
                                switch(result.status) {
                                    case 'success':
                                        swal({
                                            title: 'Done!',
                                            text: 'Article "' + page_title.val() + '" has been added',
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

                                $('#add-article').find('[type="submit"]').html('Add').attr('disabled', false);
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>