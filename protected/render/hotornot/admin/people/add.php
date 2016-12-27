<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Hot or not</title>

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
        <? 
        APP::Render('admin/widgets/header', 'include', [
            '"Hot or not" people' => 'admin/hotornot/people'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="add-people" class="form-horizontal" role="form">
                            <div class="card-header">
                                <h2>Add people</h2>
                            </div>
                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">E-Mail</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="email" id="email">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="name" id="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="city" class="col-sm-2 control-label">City</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="city" id="city">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Image (450x650 px)</label>
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
                                    <label for="description" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <div class="fg-line">
                                            <textarea name="description" id="description" class="form-control"></textarea>
                                        </div>
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
                autosize($('#description'));
                
                var $image_crop;

                $image_crop = $('#upload-demo').croppie({
                    viewport: {
                        width: 450,
                        height: 650
                    },
                    boundary: {
                        width: '100%',
                        height: 850
                    },
                    enableExif: true
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
                
                $('#add-people').submit(function(event) {
                    var form = this;
                    event.preventDefault();
                    
                    $image_crop.croppie('result', {
                        type: 'canvas',
			size: 'viewport'
                    }).then(function(b64image) {
                        $('#image').val(b64image);
                        
                        var email = $(form).find('#email');
                        var name = $(form).find('#name');
                        var city = $(form).find('#city');

                        email.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                        name.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                        city.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                        
                        if (email.val() === '') { email.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                        if (name.val() === '') { name.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                        if (city.val() === '') { city.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-10').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                        $(form).find('[type="submit"]').html('Processing...').attr('disabled', true);

                        $.ajax({
                            type: 'post',
                            url: '<?= APP::Module('Routing')->root ?>admin/hotornot/people/api/add.json',
                            data: $(form).serialize(),
                            success: function(result) {
                                switch(result.status) {
                                    case 'success':
                                        swal({
                                            title: 'Done!',
                                            text: 'People "' + name.val() + '" has been added',
                                            type: 'success',
                                            showCancelButton: false,
                                            confirmButtonText: 'Ok',
                                            closeOnConfirm: false
                                        }, function(){
                                            window.location.href = '<?= APP::Module('Routing')->root ?>admin/hotornot/people';
                                        });
                                        break;
                                    case 'error': 
                                        $.each(result.errors, function(i, error) {
                                            switch(error) {}
                                        });
                                        break;
                                }

                                $('#add-people').find('[type="submit"]').html('Add').attr('disabled', false);
                            }
                        });
                    });
                });
            });
        </script>
    </body>
  </html>