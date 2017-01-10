<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP-shell - Add product</title>

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
        'Products' => 'admin/billing/products'
    ]);
    ?>
    <section id="main">
        <? APP::Render('admin/widgets/sidebar') ?>

        <section id="content">
            <div class="container">
                <div class="card">
                    <form id="add-product" class="form-horizontal" role="form">
                        <div class="card-header">
                            <h2>Add product</h2>
                        </div>
                        <div class="card-body card-padding">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-3">
                                    <div class="fg-line">
                                        <input type="text" class="form-control" name="name" id="name">
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
                                <label for="descr_link" class="col-sm-2 control-label">Description link</label>
                                <div class="col-sm-3">
                                    <div class="fg-line">
                                        <input type="text" class="form-control" name="descr_link" id="descr_link">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="access_link" class="col-sm-2 control-label">Access link</label>
                                <div class="col-sm-3">
                                    <div class="fg-line">
                                        <input type="text" class="form-control" name="access_link" id="access_link">
                                    </div>
                                </div>
                            </div>
                                                                                                                                                                                                                                                            <div class="form-group">
                                <label for="members_access" class="col-sm-2 control-label">Access members</label>
                                <div class="col-sm-3">
                                    <div class="fg-line">
                                        <input type="text" class="form-control" name="members_access" id="members_access">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Secondary products</label>
                                <div class="col-md-5">
                                    <div id="secondary-products"></div>
                                    <button id="add-secondary-product" type="button" class="btn btn-default btn-labeled fa fa-plus">Add product</button>
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

    <? APP::Render('core/widgets/js') ?>

    <script>
        $(document).ready(function() {
            var secondary_products_counter = 0;

            $('#add-secondary-product').click(function() {
                secondary_products_counter ++;

                $('#secondary-products').append([
                    '<div class="row m-b-10">',
                        '<div class="col-md-6 mar-btm">',
                            '<select class="form-control selectpicker" id="secondary-products-' + secondary_products_counter + '" name="secondary_products[' + secondary_products_counter + '][id]" data-placeholder="product"></select>',
                        '</div>',
                        '<div class="col-md-5 mar-btm">',
                            '<input class="form-control" id="secondary-products-time-' + secondary_products_counter + '" name="secondary_products[' + secondary_products_counter + '][timeout]" data-placeholder="timeout" type="text">',
                        '</div>',
                        '<div class="col-md-1 mar-btm">',
                            '<button type="button" class="remove-secondary-products btn palette-Teal btn-icon bg waves-effect waves-circle waves-float zmdi zmdi-close"></button>',
                        '</div>',
                    '</div>'
                ].join(''));

                var secondary_products_options = [];

                $.each(<?= json_encode($data['products_list']) ?>, function() {
                    secondary_products_options.push('<option value="' + this.id + '">' + this.name + ' (' + this.amount + ' RUR)</option>');
                });

                $('#secondary-products-' + secondary_products_counter).append(secondary_products_options.join(''));
                $('#secondary-products-' + secondary_products_counter).selectpicker('render');
            });

            // remove-secondary-products
            $(document).on('click', '.remove-secondary-products', function () {
                $(this).closest('.row').remove();
            });

            $('#add-product').submit(function(event) {
                event.preventDefault();

                var name = $(this).find('#name');
                var amount = $(this).find('#amount');
                
                name.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                amount.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                if (name.val() === '') { name.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                if (amount.val() === '') { amount.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }                                                                                                                                      //if (members_access.val() === '') { members_access.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                $.ajax({
                    type: 'post',
                    url: '<?= APP::Module('Routing')->root ?>admin/billing/products/api/add.json',
                    data: $(this).serialize(),
                    success: function(result) {
                        switch(result.status) {
                            case 'success':
                                swal({
                                    title: 'Done!',
                                    text: 'Product "' + name.val() + '" has been added',
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Ok',
                                    closeOnConfirm: false
                                }, function(){
                                    window.location.href = '<?= APP::Module('Routing')->root ?>admin/billing/products';
                                });
                                break;
                            case 'error':
                                $.each(result.errors, function(i, error) {});
                                break;
                        }

                        $('#add-product').find('[type="submit"]').html('Add').attr('disabled', false);
                    }
                });
            });
        });
    </script>
</body>
</html>