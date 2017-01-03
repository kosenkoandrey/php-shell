<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP-shell - Invoice create</title>

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
                    <form id="add-invoice" class="form-horizontal" data-target="#create-invoice">
                        <div class="card-header">
                            <h2>Add invoice</h2>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="user_id" class="col-sm-2 control-label">Пользователь</label>
                                <div class="col-sm-3">
                                    <input type="text" name="invoice[user_id]" id="user_id" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="invoice-state" class="col-md-2 control-label">Состояние</label>
                                <div class="col-md-3">
                                    <select id="invoice-state" name="invoice[state]" class="selectpicker form-control" data-width="100%">
                                        <option value="new">не оплачен</option>
                                        <option value="processed">в работе</option>
                                        <option value="success">провести оплату после создания</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Продукты</label>
                                <div class="col-md-8">
                                    <div id="invoice-products"></div>
                                    <button id="invoice-products-add" type="button" class="btn btn-default btn-labeled fa fa-plus">Добавить продукт</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="invoice-comment" class="col-md-2 control-label">Комментарий</label>
                                <div class="col-md-6">
                                    <textarea id="invoice-comment" name="invoice[comment]" rows="7" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Сумма</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><span id="invoice-amount">0</span> руб. 00 коп.</p>
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
        var products = <?= json_encode($data['products_list']) ?>;

        var counters = {
            'packages': 0,
            'products': 0
        };

        $(document).ready(function () {
            $('#add-invoice').submit(function (event) {
                event.preventDefault();
                var user_id = $(this).find('#user_id');
                user_id.closest('.form-group').removeClass('has-error has-feedback').find('.form-control-feedback, .help-block').remove();

                if (user_id.val() === '') {
                    user_id.closest('.form-group').addClass('has-error has-feedback').find('.col-sm-3').append('<span class="zmdi zmdi-close form-control-feedback"></span><small class="help-block">Not specified</small>');
                    return false;
                }

                $(this).find('[type="submit"]').html('Processing...').attr('disabled', true);

                $.ajax({
                    type: 'post',
                    url: '<?= APP::Module('Routing')->root ?>admin/billing/invoices/api/add.json',
                    data: $(this).serialize(),
                    success: function (result) {
                        switch (result.status) {
                            case 'success':
                                swal({
                                    title: 'Done!',
                                    text: 'Invoice "' + result.invoice_id + '" has been added',
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Ok',
                                    closeOnConfirm: false
                                }, function () {
                                    window.location.href = '<?= APP::Module('Routing')->root ?>admin/billing/invoices';
                                });
                                break;
                            case 'error':
                                $.each(result.errors, function (i, error) {});
                                break;
                        }

                        $('#add-product').find('[type="submit"]').html('Add').attr('disabled', false);
                    }
                });
            });

            // ADD PRODUCT
            $('#invoice-products-add').click(function () {
                var counter_products = ++counters.products;

                $('#invoice-products').append('<div class="row"><div class="col-md-6 mar-btm"><select class="form-control selectpicker" id="inv-prod-selector-' + counter_products + '" name="invoice[products][' + counter_products + '][]" data-placeholder="выберите продукт"></select></div><div class="col-md-4 mar-btm"><div class="input-group"><input id="inv-prod-price-' + counter_products + '" name="invoice[products][' + counter_products + '][]" type="number" class="inv-prod-price form-control"><span class="input-group-addon">руб.</span></div></div><div class="col-md-2 mar-btm"><button type="button" class="remove-invoice-product btn btn-danger btn-xs bg waves-effect waves-circle waves-float zmdi zmdi-close"></button></div></div>');

                $('#inv-prod-selector-' + counter_products).append('<option value="0"></option>');

                var optgroup = new String();
                $.each(products, function () {
                    optgroup += '<option data-amount="' + this.amount + '" value="' + this.id + '">' + this.name + ' (' + this.amount + ' руб.)</option>';
                });

                $('#inv-prod-selector-' + counter_products).append(optgroup);
                $('#inv-prod-selector-' + counter_products).selectpicker('render');

                $('#inv-prod-selector-' + counter_products)
                        .on('changed.bs.select', function (e) {
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

                $('.inv-prod-price').each(function () {
                    var pp = parseInt($(this).val(), 10);
                    if ((pp ^ 0) === pp)
                        total += pp;
                });

                $('.inv-pack-price').each(function () {
                    var pp = parseInt($(this).val(), 10);
                    if ((pp ^ 0) === pp)
                        total += pp;
                });

                $('#invoice-amount').html(total.toString());
            }
        });
    </script>
</body>
</html>