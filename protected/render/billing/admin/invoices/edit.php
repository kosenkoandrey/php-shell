<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Invoice edit</title>

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
            'Invoices' => 'admin/billing/invoices'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
																								<form id="edit-invoice" class="form-horizontal" data-target="#edit-invoice">
																												<input type="hidden" name="invoice[id]" value="<?= $data['invoice']['main']['id'] ?>">
																												<div class="card-header">
                                <h2>Edit invoice</h2>
                            </div>
																												<div class="panel-body">
																																<div class="form-group">
																																				<label for="invoice-usr-id" class="col-sm-2 control-label">Дата создания</label>
																																				<div class="col-sm-3">
																																								<p class="form-control-static"><?= date('d.m.Y H:i:s', $data['invoice']['main']['cr_date']) ?></p>
																																				</div>
																																</div>
																																<div class="form-group">
																																				<label for="invoice-usr-id" class="col-sm-2 control-label">Пользователь</label>
																																				<div class="col-sm-3">
																																								<input type="text" name="invoice[user_id]" id="user_id" class="form-control" value="<?= $data['invoice']['main']['user_id'] ?>"/>
																																				</div>
																																</div>
																																<div class="form-group">
																																				<label for="invoice-state" class="col-md-2 control-label">Состояние</label>
																																				<div class="col-md-3">
																																								<select id="invoice-state" name="invoice[state]" class="selectpicker form-control" data-width="100%">
																																												<?
																																												$invoice_states = Array(
																																																'new' => 'не оплачен',
																																																'processed' => 'в работе'
																																												);

																																												foreach ($invoice_states as $invoice_state_id => $invoice_state_name) {
																																																?><option value="<?= $invoice_state_id ?>" <? if ($data['invoice']['main']['state'] == $invoice_state_id) { echo 'selected'; } ?>><?= $invoice_state_name ?></option><?
																																												}
																																												?>
																																								</select>
																																				</div>
																																</div>
																																<div class="form-group">
																																				<label class="col-md-2 control-label">Продукты</label>
																																				<div class="col-md-6">
																																								<div id="invoice-products">
																																												<?
																																												foreach ($data['invoice']['products'] as $invoice_product_index => $invoice_product) {
																																																?>
																																																<div class="row">
																																																				<div class="col-md-6 mar-btm">
																																																								<select class="selectpicker form-control" id="inv-prod-selector-<?= $invoice_product_index + 1 ?>" name="invoice[products][<?= $invoice_product_index + 1 ?>][]">
																																																												<?
																																																																foreach ((array) $data['products_list'] as $product_id => $product) {
																																																																				?><option value="<?= $product_id ?>" <? if ($invoice_product['product_id'] == $product_id) { echo 'selected'; } ?>><?= $product['name'] ?> (<?= $product['amount'] ?>)</option><?
																																																																}
																																																												?>
																																																								</select>
																																																				</div>
																																																				<div class="col-md-4 mar-btm">
																																																								<div class="input-group">
																																																												<input id="inv-prod-price-<?= $invoice_product_index + 1 ?>" name="invoice[products][<?= $invoice_product_index + 1 ?>][]" type="number" class="inv-prod-price form-control" value="<?= $invoice_product['price'] ?>">
																																																												<span class="input-group-addon">руб.</span>
																																																								</div>
																																																				</div>
																																																				<div class="col-md-2 mar-btm">
																																																								<button type="button" class="remove-invoice-product btn btn-danger btn-xs bg waves-effect waves-circle waves-float zmdi zmdi-close"></button>
																																																				</div>
																																																</div>
																																																<?
																																												}
																																												?>
																																								</div>
																																								<button id="invoice-products-add" type="button" class="btn btn-default btn-labeled fa fa-plus">Добавить продукт</button>
																																				</div>
																																</div>
																																<div class="form-group">
																																				<label class="col-sm-2 control-label">Сумма</label>
																																				<div class="col-sm-10">
																																								<p class="form-control-static"><span id="invoice-amount"><?= $data['invoice']['main']['amount'] ?></span> руб. 00 коп.</p>
																																				</div>
																																</div>
																															<div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        	<button class="btn palette-Teal bg waves-effect btn-lg" type="submit">Сохранить изменения</button> последний раз редактировался <?= date('d.m.Y H:i:s', $data['invoice']['main']['up_date']) ?>
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
        var products_list = <?= json_encode($data['products_list']) ?>;
        var counters = <?= json_encode($data['counters']) ?>;

        $(document).ready(function() {
												$('.selectpicker').selectpicker();

            $('#edit-invoice').submit(function(event) {
                event.preventDefault();
																var user_id = $(this).find('#user_id');
																user_id.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

																if (user_id.val() === '') { user_id.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>'); return false; }

																$(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

																$.ajax({
																				type: 'post',
																				url: '<?= APP::Module('Routing')->root ?>admin/billing/invoices/api/update.json',
																				data: $(this).serialize(),
																				success: function(result) {
																								switch(result.status) {
																												case 'success':
																																swal({
																																				title: 'Done!',
																																				text: 'Invoice "' + result.invoice_id + '" has been updated',
																																				type: 'success',
																																				showCancelButton: false,
																																				confirmButtonText: 'Ok',
																																				closeOnConfirm: false
																																}, function(){
																																				window.location.href = '<?= APP::Module('Routing')->root ?>admin/billing/invoices';
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

            // ADD PRODUCT
            $('#invoice-products-add').click(function() {
                var counter_products = ++ counters.products;

                $('#invoice-products').append('<div class="row"><div class="col-md-6 mar-btm"><select class="form-control selectpicker" id="inv-prod-selector-' + counter_products + '" name="invoice[products][' + counter_products + '][]" data-placeholder="выберите продукт"></select></div><div class="col-md-4 mar-btm"><div class="input-group"><input id="inv-prod-price-' + counter_products + '" name="invoice[products][' + counter_products + '][]" type="number" class="inv-prod-price form-control"><span class="input-group-addon">руб.</span></div></div><div class="col-md-2 mar-btm"><button type="button" class="remove-invoice-product btn btn-danger btn-xs bg waves-effect waves-circle waves-float zmdi zmdi-close"></button></div></div>');

                $('#inv-prod-selector-' + counter_products).append('<option value="0"></option>');

                var optgroup = new String();
																$.each(products_list, function() {
																				optgroup += '<option data-amount="' + this.amount + '" value="' + this.id + '">' + this.name + ' (' + this.amount + ' руб.)</option>';
																});

                $('#inv-prod-selector-' + counter_products).append(optgroup);
																$('#inv-prod-selector-' + counter_products).selectpicker('render');

																$('#inv-prod-selector-' + counter_products)
                .on('changed.bs.select', function(e) {
																				$('#inv-prod-price-' + counter_products)
																				.val($(this).find('option:selected').data('amount'))
																				.trigger('change');
                });
																CalcInvoiceAmount();
            });


            // REMOVE PRODUCT
            $(document).on('click', '.remove-invoice-product', function () {
                $(this).closest('.row').remove();
                CalcInvoiceAmount();
            });

            // CHANGE PACKAGE/PRODUCT PRICE
            $(document).on('propertychange change click keyup input paste', '.inv-prod-price, .inv-pack-price', function () {
                CalcInvoiceAmount();
            });

            // CALCULATE INVOICE AMOUNT
            function CalcInvoiceAmount() {
                var total = 0;

                $('.inv-prod-price').each(function() {
                    var pp = parseInt($(this).val(), 10);
                    if ((pp ^ 0) === pp) total += pp;
                });

                $('.inv-pack-price').each(function() {
                    var pp = parseInt($(this).val(), 10);
                    if ((pp ^ 0) === pp) total += pp;
                });

                $('#invoice-amount').html(total.toString());
            }

												<?
            foreach ($data['invoice']['products'] as $invoice_product_index => $invoice_product) {
                ?>
                $('#inv-prod-selector-<?= $invoice_product_index + 1 ?>')
                .on('change', function(params) {
                    if (params.selected) {
                        $('#inv-prod-price-<?= $invoice_product_index + 1 ?>')
                        .val(products_list[params.selected].price)
                        .trigger('change');
                    } else {
                        $('#inv-prod-price-<?= $invoice_product_index + 1 ?>')
                        .val('')
                        .trigger('change');
                    }
                });
                <?
            }
            ?>
        });
        </script>
    </body>
</html>