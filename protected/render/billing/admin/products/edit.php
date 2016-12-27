<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Products</title>

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
                        <form id="update-product" class="form-horizontal" role="form">
                            <input type="hidden" name="id" value="<?= APP::Module('Routing')->get['product_id_hash'] ?>">

                            <div class="card-header">
                                <h2>Edit product</h2>
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
                                    <label for="related_products" class="col-sm-2 control-label">Related products</label>
                                    <div class="col-sm-3">
                                        <div class="fg-line">
                                            <input type="text" class="form-control" name="related_products" id="related_products">
                                        </div>
                                    </div>
                                </div>
																																<div class="form-group">
																																				<label class="col-md-2 control-label">Дополнительные продукты</label>
																																				<div class="col-md-5">
																																								<div id="product-plus-products">
																																												<?
																																												if (count($data['product']['plus_products'])) { ?>
																																												<?php foreach ($data['product']['plus_products'] as $key => $product) {
																																																				?>
																																																				<div class="row">
																																																								<div class="col-md-6 mar-btm">
																																																												<select class="form-control selectpicker" id="product-plus-products-<?= $key ?>" name="plus_products[<?= $key ?>][id]" data-placeholder="select product">
																																																																<? foreach ((array) $data['products'] as $product_id => $product_data) { ?>
																																																																				<option value="<?= $product_id ?>" <? if ($product['id'] == $product_id) { echo 'selected'; } ?>><?= $product_data['name'] ?></option>
																																																																<?	} ?>
																																																												</select>
																																																								</div>
																																																								<div class="col-md-5 mar-btm">
																																																												<input class="form-control" value="<?= $product['time'] ?>" id="product-plus-products-time-<?= $key ?>" name="plus_products[<?= $key ?>][time]" data-placeholder="time" type="text">
																																																								</div>
																																																								<div class="col-md-1 mar-btm">
																																																												<button type="button" class="remove-product-plus-products btn btn-danger btn-xs bg waves-effect waves-circle waves-float zmdi zmdi-close"></button>
																																																								</div>
																																																				</div>
																																																				<?
																																																}
																																												}
																																												?>
																																								</div>
																																								<button id="product-plus-products-add" type="button" class="btn btn-default btn-labeled fa fa-plus">Добавить продукт</button>
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

																var products = <?= json_encode($data['products']) ?>;
																// product-plus-products
																$('#product-plus-products-add').click(function() {
																				var counters = {
																								'plus_products': <?= count($data['product']['plus_products']) ?>
																				};
																				var counter_products = ++counters.plus_products;

																				$('#product-plus-products').append([
																								'<div class="row">',
																												'<div class="col-md-6 mar-btm">',
																																'<select class="form-control selectpicker" id="product-plus-products-' + counter_products + '" name="plus_products[' + counter_products + '][id]" data-placeholder="product"></select>',
																												'</div>',
																												'<div class="col-md-5 mar-btm">',
																																'<input class="form-control" id="product-plus-products-time-' + counter_products + '" name="plus_products[' + counter_products + '][time]" data-placeholder="time" type="text">',
																												'</div>',
																												'<div class="col-md-1 mar-btm">',
																																'<button type="button" class="remove-product-plus-products btn btn-danger btn-xs bg waves-effect waves-circle waves-float zmdi zmdi-close"></button>',
																												'</div>',
																								'</div>'
																				].join(''));

																				$('#product-plus-products-' + counter_products).append('<option value="0">Select product</option>');

																				var product = new String();

																				$.each(products, function() {
																								product += '<option value="' + this.id + '">' + this.name + '</option>';
																				});

																				$('#product-plus-products-' + counter_products)
																				.append(product);
																				$('#product-plus-products-' + counter_products).selectpicker('render');
																});

																// remove-product-plus-products
																$(document).on('click', '.remove-product-plus-products', function () {
																				$(this).closest('.row').remove();
																				if($('#product-plus-products .row').length <= 1) {
																								$('#product_plus_time').hide();
																				}
																});

																$('#access_link').val('<?= $data['product']['access_link'] ?>');
                $('#descr_link').val('<?= $data['product']['descr_link'] ?>');
                $('#name').val('<?= $data['product']['name'] ?>');
                $('#amount').val('<?= $data['product']['amount'] ?>');
																$('#members_access').val('<?= $data['product']['members_access'] ?>');
																$('#related_products').val('<?= $data['product']['related_products'] ?>');

                $('#update-product').submit(function(event) {
                    event.preventDefault();

                    var descr_link = $(this).find('#descr_link');
                    var access_link = $(this).find('#access_link');
                    var name = $(this).find('#name');
                    var amount = $(this).find('#amount');
																				var members_access = $(this).find('#members_access');

                    descr_link.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    access_link.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    name.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
                    amount.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();
																				//members_access.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                    if (descr_link.val() === '') { descr_link.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (access_link.val() === '') { access_link.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (name.val() === '') { name.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
                    if (amount.val() === '') { amount.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }
																				//if (members_access.val() === '') { members_access.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

                    $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/billing/products/api/update.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Done!',
                                        text: 'Product "' + name.val() + '" has been updated',
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

                            $('#update-product').find('[type="submit"]').html('Save changes').attr('disabled', false);
                        }
                    });
                  });
            });
        </script>
    </body>
  </html>