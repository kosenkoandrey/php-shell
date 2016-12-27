<?
if ($data['coupon'] AND $data['coupon']['state'] == 'open') {
    if ($data['coupon']['settings']['amount'] > $data['invoice']['amount'])
        $data['invoice']['amount'] = 0;
    else
        $data['invoice']['amount'] = $data['invoice']['amount'] - $data['coupon']['settings']['amount'];
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>Оплата заказа <?= $data['invoice']['id'] ?></title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Оплата заказа <?= $data['invoice']['id'] ?>">
    <meta name="robots" content="none">

	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700,300,500" rel="stylesheet" type="text/css">
	<link href="<?= APP::Module('Routing')->root ?>public/nifty/ui/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= APP::Module('Routing')->root ?>public/nifty/ui/css/nifty.min.css" rel="stylesheet">
	<link href="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/animate-css/animate.min.css" rel="stylesheet">
	<link href="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/switchery/switchery.min.css" rel="stylesheet">
	<link href="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/bootstrap-validator/bootstrapValidator.min.css" rel="stylesheet">

        <!--Page Load Progress Bar [ OPTIONAL ]-->
	<link href="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/pace/pace.min.css" rel="stylesheet">
	<script src="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/pace/pace.min.js"></script>

        <!-- OPTIONAL -->
        <style>
            .form-control {
                font-size: 14px !important;
                height: 34px !important;
            }
            textarea.form-control {
                height: auto !important;
            }

            #invoice-info,
            #payment-wizard {
                font-size: 14px;
            }
            .wz-classic li > a {
                padding: 15px 0;
                text-transform: uppercase;
                font-weight: bold;
            }
            .wz-classic li>a .icon-wrap {
                margin-right: 5px;
            }
            .panel-body {
                padding: 25px 30px;
            }

            .method {
                text-align: center;
                height: 145px;
                padding: 5px;
                background: #FAFAFA;
                cursor: pointer;
                border: 1px solid #F0F0F0;
            }
            .method.v2 {
                padding: 10px 5px;
            }
            .method:hover {
                background: #EFEFEF;
            }
            .operator {
                display: none;
            }




            .strike {
                display: block;
                text-align: center;
                overflow: hidden;
                white-space: nowrap;
            }

            .strike > span {
                position: relative;
                display: inline-block;
                text-transform: uppercase;
            }

            .strike > span:before,
            .strike > span:after {
                content: "";
                position: absolute;
                top: 50%;
                width: 9999px;
                height: 1px;
                background: rgba(0,0,0,0.1);
            }

            .strike > span:before {
                right: 100%;
                margin-right: 10px;
            }

            .strike > span:after {
                left: 100%;
                margin-left: 10px;
            }
        </style>
    </head>
    <body>
        <!-- Google Tag Manager -->
        <!--<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KZKJM6"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KZKJM6');</script>-->
        <!-- End Google Tag Manager -->

        <form class="operator" id="operator-yandex" action="https://money.yandex.ru/eshop.xml" method="post">
            <input type="hidden" name="shopId" value="19572">
            <input type="hidden" name="scid" value="10179">
            <input type="hidden" name="cps_email" value="<?= $data['invoice']['email'] ?>">
            <input type="hidden" name="orderNumber" value="<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="sum" value="<?= $data['invoice']['amount'] ?>">
            <input type="hidden" name="customerNumber" value="<?= $data['invoice']['user_id'] ?>">
            <select name="paymentType">
                <option value="PC">Оплата из кошелька в Яндекс.Деньгах</option>
                <option value="AC">Оплата с произвольной банковской карты</option>
                <option value="MC">Платеж со счета мобильного телефона</option>
                <option value="GP">Оплата наличными через кассы и терминалы</option>
                <option value="WM">Оплата из кошелька в системе WebMoney</option>
                <option value="SB">Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн</option>
                <option value="MP">Оплата через мобильный терминал (mPOS)</option>
                <option value="AB">Оплата через Альфа-Клик</option>
            </select>
            <input type="hidden" name="shopSuccessURL" value="<?php // echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/success/<?= APP::Module('Routing')->get['invoice_id'] ?>">
            <input type="hidden" name="shopFailURL" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/fail/<?= APP::Module('Routing')->get['invoice_id'] ?>">
        </form>

        <form class="operator" id="operator-paypal" action="https://www.paypal.com/row/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="amount" value="<?= $data['invoice']['amount'] ?>">
            <input type="hidden" name="item_name" value="ORDER #<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="currency_code" value="RUB">
            <input type="hidden" name="custom" value="<?= $data['invoice']['user_id'] ?>">
            <input type="hidden" name="invoice" value="<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="business" value="kosenko-andrey@yandex.ru">
            <input type="hidden" name="lc" value="RU">
            <input type="hidden" name="display" value="0">
            <input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="no_note" value="1">
            <input type="hidden" name="return" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/success/<?= APP::Module('Routing')->get['invoice_id'] ?>">
            <input type="hidden" name="cancel_return" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/fail/<?= APP::Module('Routing')->get['invoice_id'] ?>">
            <input type="hidden" name="notify_url" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/operators/paypal/ipn">
            <input type="hidden" name="email" value="<?= $data['invoice']['email'] ?>">
            <input type="hidden" name="charset" value="utf-8">
        </form>

        <form class="operator" id="operator-rbk" action="https://rbkmoney.ru/acceptpurchase.aspx" method="post">
            <input type="hidden" name="eshopId" value="2008752">
            <input type="hidden" name="orderId" value="<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="serviceName" value="ORDER #<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="recipientAmount" value="<?= $data['invoice']['amount'] ?>.00">
            <input type="hidden" name="recipientCurrency" value="RUR">
            <input type="hidden" name="user_email" value="<?= $data['invoice']['email'] ?>">
            <select name="preference">
                <option value="bankcard">Банковская карта Visa/MasterCard</option>
                <option value="sberbank">Банковский платеж</option>
                <option value="ibank">Интернет банкинг</option>
                <option value="terminals">Платежные терминалы</option>
                <option value="mobilestores">Салоны связи</option>
                <option value="postrus">Почта России</option>
                <option value="transfers">Системы денежных переводов</option>
                <option value="inner">Кошелек RBK Money</option>
                <option value=""></option>
            </select>
            <input type="hidden" name="successUrl" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/success/<?= APP::Module('Routing')->get['invoice_id'] ?>">
            <input type="hidden" name="failUrl" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/fail/<?= APP::Module('Routing')->get['invoice_id'] ?>">
            <input type="hidden" name="userField_uid" value="<?= $data['invoice']['user_id'] ?>">
        </form>

        <form class="operator" id="operator-2checkout" action="https://www.2checkout.com/checkout/purchase" method="post">
            <input type="hidden" name="sid" value="1549572">
            <input type="hidden" name="userid" value="glamurnenko">
            <input type="hidden" name="fixed" value="Y">
            <input type="hidden" name="email" value="<?= $data['invoice']['email'] ?>">
            <input type="hidden" name="skip_landing" value="1">
            <input type="hidden" name="id_type" value="1">
            <input type="hidden" name="c_prod" value="<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="cart_order_id" value="<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="merchant_order_id" value="<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="merchant_uid" value="<?= $data['invoice']['user_id'] ?>">
            <input type="hidden" name="pay_method" value="CC">
            <input type="hidden" name="currency_code" value="RUB">
            <input type="hidden" name="total" value="<?= $data['invoice']['amount'] ?>">
            <input type="hidden" name="return_url" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/success/<?= APP::Module('Routing')->get['invoice_id'] ?>">
            <input type="hidden" name="c_name" value="ORDER <?= $data['invoice']['id'] ?>">
            <input type="hidden" name="c_description" value="na">
            <input type="hidden" name="c_price" value="<?= $data['invoice']['amount'] ?>">
        </form>

        <form class="operator" id="operator-z-payment" action="https://z-payment.com/merchant.php" method="post">
            <input type="hidden" name="LMI_PAYEE_PURSE" value="6998">
            <input type="hidden" name="LMI_PAYMENT_NO" value="<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?= $data['invoice']['amount'] ?>.00">
            <input type="hidden" name="LMI_PAYMENT_DESC" value="ORDER <?= $data['invoice']['id'] ?>">
            <input type="hidden" name="CLIENT_MAIL" value="<?= $data['invoice']['email'] ?>">
            <input type="hidden" name="oid" value="<?= $data['invoice']['id'] ?>">
        </form>

        <form class="operator" id="operator-robokassa" action="https://auth.robokassa.ru/Merchant/Index.aspx" method="post">
            <input type="hidden" name="MrchLogin" value="kosenko">
            <input type="hidden" name="OutSum" value="<?= $data['invoice']['amount'] ?>.00">
            <input type="hidden" name="InvId" value="<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="Desc" value="Оплата заказа <?= $data['invoice']['id'] ?>">
            <input type="hidden" name="SignatureValue" value="<?= md5('kosenko:' . $data['invoice']['amount'] . '.00:' . $data['invoice']['id'] . ':A82jdsDS84jjdfdff:SHP_oid=' . $data['invoice']['id']) ?>">
            <input type="hidden" name="Email" value="<?= $data['invoice']['email'] ?>">
            <input type="hidden" name="Encoding" value="utf-8">
            <input type="hidden" name="SHP_oid" value="<?= $data['invoice']['id'] ?>">
        </form>


        <? // W1

        //Секретный ключ интернет-магазина
        $key = "764b653362417847524d417c62777b576031783578694f7c50315e";

        $fields = array();

        // Добавление полей формы в ассоциативный массив
        $fields['WMI_MERCHANT_ID']      = '192280201731';
        $fields['WMI_PAYMENT_AMOUNT']   = $data['invoice']['amount'] . '.00';
        $fields['WMI_CURRENCY_ID']      = "643";
        $fields['WMI_CULTURE_ID']       = 'ru-RU';
        $fields['WMI_PAYMENT_NO']       = $data['invoice']['id'];
        $fields['WMI_DESCRIPTION']      = 'BASE64:'.base64_encode('Оплата заказа ' . $data['invoice']['id']);
        $fields['WMI_SUCCESS_URL']      = //APP::Module('Routing')->root . APP::Module('Billing')->uri.'billing/payments/success/' . $_GET['invoice_id'];
        $fields['WMI_FAIL_URL']         = //APP::Module('Routing')->root . APP::Module('Billing')->uri.'billing/payments/fail/' . $_GET['invoice_id'];
        $fields['oid']                  = $data['invoice']['id']; // Дополнительные параметры (номер счета)
        //Если требуется задать только определенные способы оплаты, раскоментируйте данную строку и перечислите требуемые способы оплаты.
        //$fields["WMI_PTENABLED"]      = array("ContactRUB", "UnistreamRUB", "SberbankRUB", "RussianPostRUB");

        //Сортировка значений внутри полей
        foreach($fields as $name => $val) {
            if (is_array($val)) {
               usort($val, "strcasecmp");
               $fields[$name] = $val;
            }
        }

        // Формирование сообщения, путем объединения значений формы,
        // отсортированных по именам ключей в порядке возрастания.
        uksort($fields, "strcasecmp");
        $fieldValues = "";

        foreach($fields as $value) {
            if (is_array($value)) {
               foreach($value as $v) {
                    //Конвертация из текущей кодировки (UTF-8)
                    //необходима только если кодировка магазина отлична от Windows-1251
                    $v = iconv("utf-8", "windows-1251", $v);
                    $fieldValues .= $v;
               }
            } else {
                //Конвертация из текущей кодировки (UTF-8)
                //необходима только если кодировка магазина отлична от Windows-1251
                $value = iconv("utf-8", "windows-1251", $value);
                $fieldValues .= $value;
            }
        }

        // Формирование значения параметра WMI_SIGNATURE, путем
        // вычисления отпечатка, сформированного выше сообщения,
        // по алгоритму MD5 и представление его в Base64
        $signature = base64_encode(pack("H*", md5($fieldValues . $key)));

        //Добавление параметра WMI_SIGNATURE в словарь параметров формы
        $fields["WMI_SIGNATURE"] = $signature;

        // Формирование HTML-кода платежной формы
        ?>
        <form class="operator" id="operator-w1" action="https://www.walletone.com/checkout/default.aspx" method="post">
            <?
            foreach($fields as $key => $val) {
                if (is_array($val)) {
                   foreach($val as $value) {
                       ?><input type="hidden" name="<?= $key ?>" value="<?= $value ?>"><?
                   }
                } else {
                    ?><input type="hidden" name="<?= $key ?>" value="<?= $val ?>"><?
                }
            }
            ?>
        </form>

        <form class="operator" id="operator-wm" action="https://merchant.webmoney.ru/lmi/payment.asp" method="post">
            <input type="hidden" name="LMI_PAYEE_PURSE" value="R892677526867">
            <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?= $data['invoice']['amount'] ?>.00">
            <input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="<?= base64_encode('Оплата заказа ' . $data['invoice']['id']) ?>">
            <input type="hidden" name="LMI_PAYMENT_NO" value="<?= $data['invoice']['id'] ?>">
            <input type="hidden" name="LMI_SUCCESS_URL" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/success/<?= APP::Module('Routing')->get['invoice_id'] ?>">
            <input type="hidden" name="LMI_SUCCESS_METHOD" value="GET">
            <input type="hidden" name="LMI_FAIL_URL" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/fail/<?= APP::Module('Routing')->get['invoice_id'] ?>">
            <input type="hidden" name="LMI_FAIL_METHOD" value="GET">
            <input type="hidden" name="LMI_PAYMER_EMAIL" value="<?= $data['invoice']['email'] ?>">
            <input type="hidden" name="LMI_RESULT_URL" value="<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/operators/wm/ipn">
            <input type="hidden" name="oid" value="<?= $data['invoice']['id'] ?>">
        </form>

        <div class="modal fade" role="dialog" id="modal-qiwi-tel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">Введите номер телефона, зарегистрированного в системе QIWI</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-inline">
                            <div class="form-group">
                                <label for="qiwi-tel">Номер телефона</label>
                                <input type="text" class="form-control" id="qiwi-tel" placeholder="например: +79223334455">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="set-qiwi-tel">Перейти к оплате</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" role="dialog" id="modal-ukr-card" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">Оплата в Украине на карточку</h4>
                    </div>
                    <div class="modal-body">
                        <b>Ваша сумма для оплаты = <?php //echo ceil($data['invoice']['amount'] / $data['currency']['UAH']['value']); ?> гривен</b>
                        <br><br>
                        Visa  4073 6131 0006 2896
                        <br>
                        Укрсиббанк
                        <br>
                        Получатель: Ходоровская Анна Александровна
                        <br><br>
                        Деньги можно перевести в отделении Укрсиббанка или любого банка Украины (возможна дополнительная комиссия), сообщив операционисту:
                        <ul>
                            <li>номер карты</li>
                            <li>ФИО Получателя</li>
                            <li>ФИО Отправителя</li>
                        </ul>
                        После оплаты необходимо прислать на почту admin@glamurnenko.ru:
                        <ul>
                            <li>фото платежа</li>
                            <li>указать e-meil, на который был оформлен заказ</li>
                            <li>Ваше ФИО</li>
                        </ul>
                        Для оплаты через систему Приват24 используйте номер карты, указанный выше, а также МФО 351005, ЕДРПОУ  09807750 (Ганна Ходоровська).
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container" id="page-content">
												<? APP::Render('billing/payments/nifty_header') ?>
        </div>

        <div id="payment-wizard">
            <ul class="wz-nav-off wz-icon-inline">
                <li class="col-xs-4 bg-gray-light">
                    <a data-toggle="tab" href="#payment-wizard-details">
                        <span class="icon-wrap icon-wrap-xs bg-light"><i class="fa fa-info"></i></span> Данные заказа
                    </a>
                </li>
                <li class="col-xs-4 bg-gray-light">
                    <a data-toggle="tab" href="#payment-wizard-method">
                        <span class="icon-wrap icon-wrap-xs bg-light"><i class="fa fa-money"></i></span> Выбор способа оплаты
                    </a>
                </li>
                <li class="col-xs-4 bg-gray-light">
                    <a data-toggle="tab" href="#payment-wizard-finish">
                        <span class="icon-wrap icon-wrap-xs bg-light"><i class="fa fa-check"></i></span> Получение доступа
                    </a>
                </li>
            </ul>

            <div class="progress progress-sm progress-striped active mar-btm">
                <div class="progress-bar progress-bar-primary"></div>
            </div>

            <div id="invoice-info" class="panel-body">
                <div class="row mar-btm">
                    <div class="col-lg-2 text-right">
                        Номер заказа
                    </div>
                    <div class="col-lg-10">
                        <?= $data['invoice']['id'] ?>
                    </div>
                </div>
                <?
                if (count((array) $data['packages'])) {
                    ?>
                    <div class="row mar-btm">
                        <div class="col-lg-2 text-right">
                            Комплекты продуктов
                        </div>
                        <div class="col-lg-10">
                            <?
                            foreach (array_reverse($data['packages']) as $package) {
                                $package_name = $package['group_name'] . $package['name'] ? $package['group_name'] . ' &mdash; ' . $package['name'] : 'Свободная комплектация';
                                ?>
                                <div class="row mar-btm">
                                    <div class="col-lg-10">
                                        <b><?= $package_name ?></b>
                                        <hr class="mar-no">
                                    </div>
                                    <div class="col-lg-2 bg-gray-light">
                                        <?= $package['price'] ?> руб.
                                    </div>
                                </div>
                                <?
                                foreach (array_reverse($package['products']) as $package_product) {
                                    ?>
                                    <div class="row mar-btm">
                                        <div class="col-lg-10">
                                            <?= $package_product['name'] ?>
                                            <hr class="mar-no">
                                        </div>
                                        <div class="col-lg-2 bg-gray-light">
                                            &mdash;
                                        </div>
                                    </div>
                                    <?
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?
                }

                if (count((array) $data['products'])) {
                    ?>
                    <div class="row mar-btm">
                        <div class="col-lg-2 text-right">
                            Продукты
                        </div>
                        <div class="col-lg-10">
                            <?
                            foreach (array_reverse($data['products']) as $product) {
                                ?>
                                <div class="row mar-btm">
                                    <div class="col-md-10">
                                        <?= $product['name'] ?>
                                        <hr class="mar-no">
                                    </div>
                                    <div class="col-md-2 bg-gray-light">
                                        <?= $product['price'] ?> руб.
                                    </div>
                                </div>
                                <?
                            }
                            if ($data['coupon'] AND $data['coupon']['state'] == 'open') {
                            ?>
                            <div class="row mar-btm">
                                <div class="col-md-10">
                                    Скидка
                                    <hr class="mar-no">
                                </div>
                                <div class="col-md-2 bg-gray-light">
                                    <?= $data['coupon']['settings']['amount'] ?> руб.
                                </div>
                            </div>
                            <? } ?>
                        </div>
                    </div>
                    <?
                }
                ?>
                <div class="row">
                    <div class="col-lg-offset-2 col-lg-10">
                        <div class="row">
                            <div class="col-md-10 text-right text-bold">
                                Итого к оплате
                            </div>
                            <div class="col-md-2 text-bold bg-success">
                                <?= $data['invoice']['amount'] ?> руб.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mar-no">

            <div class="panel-body">
                <div class="tab-content">
                    <div id="payment-wizard-details" class="tab-pane">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row"><div class="col-lg-offset-3 col-lg-9"><h4 class="mar-btm">Ваша контактная информация</h4></div></div>
                                <form id="invoice-details" class="form-horizontal" data-target="#payment-wizard-details">
                                    <input type="hidden" name="invoice_id" value="<?= $data['invoice']['id'] ?>">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Фамилия</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" name="lastname" value="<?= $data['invoice_details']['lastname'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Имя</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" name="firstname" value="<?= $data['invoice_details']['firstname'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Телефон</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" name="tel" value="<?= $data['invoice_details']['tel'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">E-Mail</label>
                                        <div class="col-lg-9">
                                            <input type="email" class="form-control" name="email" value="<?= $data['invoice_details']['email'] ? $data['invoice_details']['email'] : $data['invoice']['email'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Комментарий к заказу</label>
                                        <div class="col-lg-9">
                                            <textarea rows="10" class="form-control" name="comment"><?= $data['invoice_details']['comment'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-offset-3 col-lg-9">
                                            <button type="submit" class="btn btn-success btn-lg" id="make_payment" onclick="yaCounter25568678.reachGoal('choosepayment-pult');">Перейти к оплате</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <h4 class="mar-btm">Отзывы</h4>
                                <div class="alert alert-primary fade in">
                                    <p class="text-bold">Катя научила нас как грамотно подчеркнуть свои достоинства, как правильно выбирать вещи, и самое главное, как их правильно компоновать.</p>
                                    <p>Марина, 37 лет, в декретном отпуске</p>
                                </div>
                                <div class="alert alert-primary fade in">
                                    <p class="text-bold">Когда читала информацию о нем, зацепила фраза: "Учтите, это будет "точкой невозврата". Для меня этот тренинг именно тем и стал.</p>
                                    <p>Ольга Костина, г. Челябинск, программист</p>
                                </div>
                                <div class="alert alert-primary fade in">
                                    <p class="text-bold">Для меня посещение магазинов было каторгой. А теперь есть интерес. Интересно смотреть на вещи, применяя к ним новые знания, и конечно мерить.</p>
                                    <p>Ольга Розанова, 32 года, г. Москва, маркетолог</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="payment-wizard-method" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4>Выберите способ оплаты</h4>
                                <div class="row" id="primary-payment-methods">
                                    <div class="col-lg-3"><div data-target="#payment-wizard-method" class="method v2 mar-btm" data-operator="yandex" data-method="AC"><img src="/public/nifty/images/payment/card.png"><br>Банковская карта</div></div>
                                    <div class="col-lg-3"><div data-target="#payment-wizard-method" class="method v2 mar-btm" data-operator="yandex" data-method="PC"><img src="/public/nifty/images/payment/yandex-money.png"><br>Яндекс.Деньги</div></div>
                                    <div class="col-lg-3"><div data-target="#payment-wizard-method" class="method v2 mar-btm" data-operator="paypal" data-method=""><img src="/public/nifty/images/payment/paypal.png"><br>PayPal</div></div>
                                    <div class="col-lg-3"><div data-target="#payment-wizard-method" class="method  mar-btm" data-operator="bank" data-method="pp"><img src="/public/nifty/images/payment/bank-pp.png"><br>Банковский платеж<br>(физ. лицо)</div></div>
                                    <div class="col-lg-3"><div data-target="#payment-wizard-method" class="method v2 mar-btm" data-operator="2checkout" data-method=""><img src="/public/nifty/images/payment/2checkout.png"><br>2checkout</div></div>
                                    <div class="col-lg-3"><div data-target="#payment-wizard-method" class="method  mar-btm" data-operator="yandex" data-method="SB"><img src="/public/nifty/images/payment/sberbank.png"><br>Сбербанк Онлайн<br>или SMS</div></div>
                                    <div class="col-lg-3"><div data-target="#payment-wizard-method" class="method v2 mar-btm" data-operator="wm" data-method=""><img src="/public/nifty/images/payment/wm.png"><br>WebMoney</div></div>
                                    <div class="col-lg-3"><div data-target="#payment-wizard-method" class="method v2 mar-btm" data-operator="qiwi" data-method=""><img src="/public/nifty/images/payment/qiwi.png"><br>QIWI кошелек</div></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group accordion" id="accordion">
                            <div class="panel panel-bordered panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-parent="#accordion" data-toggle="collapse" href="#other-methods"><h4><i class="fa fa-arrow-right"></i> Выбрать другой способ оплаты</h4></a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse" id="other-methods">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <h4>Выберите страну</h4>
                                                <div class="form-group">
                                                    <div class="pad-no form-block">
                                                        <label class="form-radio form-normal active"><input type="radio" checked="" class="payment-method-filter" name="payment-method-filter-country" value="ru"> Россия</label>
                                                        <label class="form-radio form-normal"><input type="radio" class="payment-method-filter country-filter" name="payment-method-filter-country" value="ua"> Украина</label>
                                                        <label class="form-radio form-normal"><input type="radio" class="payment-method-filter country-filter" name="payment-method-filter-country" value="by"> Беларусь</label>
                                                        <label class="form-radio form-normal"><input type="radio" class="payment-method-filter country-filter" name="payment-method-filter-country" value="kz"> Казахстан</label>
                                                        <label class="form-radio form-normal"><input type="radio" class="payment-method-filter country-filter" name="payment-method-filter-country" value="other"> Дальнее Зарубежье</label>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h4>Выберите способ оплаты</h4>
                                                <div class="form-group">
                                                    <div class="pad-no form-block method-filter-list">
                                                        <label class="form-radio form-normal"><input type="radio" class="payment-method-filter method-filter" name="payment-method-filter-type" value="cards"> Банковские карты</label>
                                                        <label class="form-radio form-normal"><input type="radio" class="payment-method-filter method-filter" name="payment-method-filter-type" value="ecash"> Электронные деньги</label>
                                                        <label class="form-radio form-normal"><input type="radio" class="payment-method-filter method-filter" name="payment-method-filter-type" value="bank"> Банки России</label>
                                                        <label class="form-radio form-normal"><input type="radio" class="payment-method-filter method-filter" name="payment-method-filter-type" value="terminal"> Через терминалы,<br>салоны связи</label>
                                                        <label class="form-radio form-normal"><input type="radio" class="payment-method-filter method-filter" name="payment-method-filter-type" value="remittance"> Денежные переводы</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div id="payments-methods-list"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-inline">
                            <button onclick="$('#payment-wizard').bootstrapWizard('previous'); $('#make_payment').removeAttr('disabled');" type="button" class="btn btn-default btn-lg"><i class="fa fa-undo"></i> Вернуться к заполнению данных заказа</button>
                        </div>
                    </div>
                    <div id="payment-wizard-finish" class="tab-pane mar-btm"></div>
                </div>
            </div>
        </div>

        <hr class="mar-no">

        <div class="panel-body">
												<? APP::Render('billing/payments/nifty_footer') ?>
        </div>

        <script src="<?= APP::Module('Routing')->root ?>public/nifty/ui/js/jquery-2.1.1.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/nifty/ui/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/nifty/ui/js/nifty.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/switchery/switchery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/bootstrap-select/bootstrap-select.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/backstretch/jquery.backstretch.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/bootstrap-validator/bootstrapValidator.min.js"></script>

        <!-- OPTIONAL -->
        <script>
        var gtag_vk_ret = false;
        var invoice_details = new Object();

        var payments_methods = {
            'card': {
                'class'     : 'v2',
                'operator'  : 'yandex',
                'method'    : 'AC',
                'name'      : 'Банковская карта',
                'filters'   : {
                    'country': [
                        'ru',
                        'ua',
                        'by',
                        'kz',
                        'other'
                    ],
                    'type': [
                        'cards'
                    ]
                }
            },
            'yandex-money': {
                'class'     : 'v2',
                'operator'  : 'yandex',
                'method'    : 'PC',
                'name'      : 'Яндекс.Деньги',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'ecash'
                    ]
                }
            },
            'paypal': {
                'class'     : 'v2',
                'operator'  : 'paypal',
                'method'    : '',
                'name'      : 'PayPal',
                'filters'   : {
                    'country': [
                        'ru',
                        'ua',
                        'by',
                        'kz',
                        'other'
                    ],
                    'type': [
                        'cards',
                        'ecash'
                    ]
                }
            },
            'rbk-money': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : '',
                'name'      : 'RBK Money',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'cards'
                    ]
                }
            },
            'eleksnet': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Терминалы Элекснет',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'esgp': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Терминалы ЕСГП',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'svobodnaya-kassa': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Свободная касса',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'euroset': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'mobilestores',
                'name'      : 'Через Евросеть',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'mobil-element': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'mobilestores',
                'name'      : 'Мобил Элемент',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'pinpay': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Pinpay',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'gorod-system': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Система «Город»',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'russian-post': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'postrus',
                'name'      : 'ФГУП «Почта России»',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'multi-kassa': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Мульти-Касса',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'skysend': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'SkySend',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'quickpay': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'QuickPay',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'lider': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'transfers',
                'name'      : 'ЛИДЕР',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'dig-bank': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Диг-Банк',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'ciberpay': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'CiberPay',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'cybercity': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'СyberCity',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'regplat': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'RegPlat',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'telepay': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Telepay',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'absolut-plat': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Абсолют Плат',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'ugra-express': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'Югра-Экспресс',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'yapk': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'ЯПК',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'deltapay': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'DeltaPay',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'nps': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'N-PS',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'mts': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'terminals',
                'name'      : 'МТС',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'russian-standart': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'ibank',
                'name'      : 'Русский Стандарт',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'alt-telecom': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'mobilestores',
                'name'      : 'Альт Телеком',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'contact': {
                'class'     : 'v2',
                'operator'  : 'rbk',
                'method'    : 'transfers',
                'name'      : 'Contact',
                'filters'   : {
                    'country': [
                        'ru',
                        'by',
                        'kz'
                    ],
                    'type': [
                        'remittance'
                    ]
                }
            },
            '2checkout': {
                'class'     : 'v2',
                'operator'  : '2checkout',
                'method'    : '',
                'name'      : '2checkout',
                'filters'   : {
                    'country': [
                        'ru',
                        'ua',
                        'by',
                        'kz',
                        'other'
                    ],
                    'type': [
                        'cards'
                    ]
                }
            },
            'bank-pp': {
                'class'     : '',
                'operator'  : 'bank',
                'method'    : 'pp',
                'name'      : 'Банковский платеж<br>(физ. лицо)',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'bank'
                    ]
                }
            },
            'bank-corp': {
                'class'     : '',
                'operator'  : 'bank',
                'method'    : 'corp',
                'name'      : 'Банковский платеж<br>(юр. лицо)',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'bank'
                    ]
                }
            },
            'qiwi': {
                'class'     : 'v2',
                'operator'  : 'qiwi',
                'method'    : '',
                'name'      : 'QIWI кошелек',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'telephone': {
                'class'     : '',
                'operator'  : 'yandex',
                'method'    : 'MC',
                'name'      : 'Cо счета мобильного<br>телефона',
                'filters'   : {
                    'country': [
                        'ru',
                        //'ua',
                        //'by',
                        'kz'
                    ],
                    'type': [

                    ]
                }
            },
            'terminal': {
                'class'     : '',
                'operator'  : 'yandex',
                'method'    : 'GP',
                'name'      : 'Наличными через<br>кассы и терминалы',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            /*
            'webmoney': {
                'class'     : 'v2',
                'operator'  : 'yandex',
                'method'    : 'WM',
                'name'      : 'WebMoney',
                'filters'   : {
                    'country': [

                    ],
                    'type': [

                    ]
                }
            },
            */
            'ukr-card': {
                'class'     : '',
                'operator'  : 'ukr-card',
                'method'    : '',
                'name'      : 'Оплата в Украине<br>на карточку',
                'filters'   : {
                    'country': [
                        'ua'
                    ],
                    'type': [
                        'cards'
                    ]
                }
            },
            'masterpass': {
                'class'     : 'v2',
                'operator'  : 'yandex',
                'method'    : 'MA',
                'name'      : 'MasterPass',
                'filters'   : {
                    'country': [
                        'ru',
                        //'ua',
                        'by',
                        'kz',
                        'other'
                    ],
                    'type': [
                        'ecash'
                    ]
                }
            },
            'psbank': {
                'class'     : 'v2',
                'operator'  : 'yandex',
                'method'    : 'PB',
                'name'      : 'Промсвязьбанк',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'terminal'
                    ]
                }
            },
            'a-bank': {
                'class'     : 'v2',
                'operator'  : 'yandex',
                'method'    : 'AB',
                'name'      : 'Альфа-Банк',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'cards'
                    ]
                }
            },
            'z-payment': {
                'class'     : 'v2',
                'operator'  : 'z-payment',
                'method'    : '',
                'name'      : 'Z-Payment',
                'filters'   : {
                    'country': [
                        'ru',
                        'ua',
                        'by',
                        'kz',
                        'other'
                    ],
                    'type': [
                        'ecash'
                    ]
                }
            },
            /*
            'robokassa': {
                'class'     : 'v2',
                'operator'  : 'robokassa',
                'method'    : '',
                'name'      : 'RoboKassa',
                'filters'   : {
                    'country': [
                        'ru',
                        'ua',
                        'by',
                        'kz'
                    ],
                    'type': [
                        'ecash'
                    ]
                }
            },
            */
            'w1': {
                'class'     : '',
                'operator'  : 'w1',
                'method'    : '',
                'name'      : 'W1<br>Единый кошелек',
                'filters'   : {
                    'country': [
                        'ru',
                        'ua',
                        'by',
                        'kz'
                    ],
                    'type': [
                        'ecash'
                    ]
                }
            },
            'wm': {
                'class'     : 'v2',
                'operator'  : 'wm',
                'method'    : '',
                'name'      : 'WebMoney',
                'filters'   : {
                    'country': [
                        'ru',
                        'ua',
                        'by',
                        'kz'
                    ],
                    'type': [
                        'ecash'
                    ]
                }
            }
        };

        <?
        if ((int) $data['invoice']['amount'] < 10000) {
            ?>
            payments_methods['sberbank'] = {
                'class'     : '',
                'operator'  : 'yandex',
                'method'    : 'SB',
                'name'      : 'Сбербанк Онлайн<br>или SMS',
                'filters'   : {
                    'country': [
                        'ru'
                    ],
                    'type': [
                        'cards'
                    ]
                }
            };
            <?
        }
        ?>

        function GeneratePaymentsMethods() {
            $('#payments-methods-list').empty();

            var payment_filter_country = $('input[name="payment-method-filter-country"]:checked');
            var payment_filter_type = $('input[name="payment-method-filter-type"]:checked');

            $.each(payments_methods, function(method_id, method) {
                if (payment_filter_country.length) {
                    if (find(method.filters.country, payment_filter_country.val()) === -1) return;
                }

                if (payment_filter_type.length) {
                    if (find(method.filters.type, payment_filter_type.val()) === -1) return;
                }

                $('#payments-methods-list').append([
                    '<div class="col-lg-4">',
                        '<div data-target="#payment-wizard-method" class="method ' + method.class + ' mar-btm" data-operator="' + method.operator + '" data-method="' + method.method + '">',
                            '<img src="<?= APP::Module('Routing')->root ?>public/nifty/images/payment/' + method_id + '.png">',
                            '<br>',
                            method.name,
                        '</div>',
                    '</div>'
                ].join(''));
            });
        }

        $('.payment-method-filter').on('click', function() {
            if ($(this).hasClass('country-filter')) {
                $('.method-filter').attr('checked', false);
                $('.method-filter-list > .form-radio').removeClass('active');
            }

            GeneratePaymentsMethods();
        });

        $.backstretch([
            "<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/backstretch/bg/4.jpg",
            "<?= APP::Module('Routing')->root ?>public/nifty/ui/plugins/backstretch/bg/5.jpg"
            ], {
                fade: 1000,
                duration: 5000
        });

        $('#payment-wizard').bootstrapWizard({
            tabClass		: 'wz-classic',
            onTabClick: function(tab, navigation, index) {
                return false;
            },
            onTabShow: function(tab, navigation, index) {
                var $total = navigation.find('li').length;
                var $current = index+1;
                var $percent = ($current/$total) * 100;
                var wdt = 100/$total;
                var lft = wdt*index;

                $('#payment-wizard').find('.progress-bar').css({width:$percent+'%'});

                if (index === 1) {
                    GeneratePaymentsMethods();
                }
            }
	});

        // SAVE INVOICE DETAILS
        $('#invoice-details').bootstrapValidator({
            message: 'Неверное значение',
            feedbackIcons: {
                valid: 'fa fa-check-circle fa-lg text-success',
                invalid: 'fa fa-times-circle fa-lg',
                validating: 'fa fa-refresh'
            },
            fields: {
                'lastname': {
                    validators: {
                        notEmpty: {
                            message: 'Введите вашу фамилию'
                        }
                    }
                },
                'firstname': {
                    validators: {
                        notEmpty: {
                            message: 'Введите ваше имя'
                        }
                    }
                },
                'tel': {
                    validators: {
                        notEmpty: {
                            message: 'Введите ваш телефон'
                        }
                    }
                },
                'email': {
                    validators: {
                        notEmpty: {
                            message: 'Введите ваш E-Mail'
                        },
                        emailAddress: {
                            message: 'E-Mail введен не корректно'
                        }
                    }
                }
            },
            onSuccess: function(e, data) {
                $('#invoice-details')
                .niftyOverlay({
                    title: 'Выполняется сохранение изменений',
                    desc: 'Пожалуйста подождите...'
                })
                .submit(function() {
                    var details_form = $(this);

                    details_form.niftyOverlay('show');
                    gtag_vk_ret = 'pay_form';

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root; ?>admin/billing/invoices/api/details/update.json',
                        data: $('#invoice-details').serialize(),
                        success: function(data) {
                            invoice_details = data.details;
                            details_form.niftyOverlay('hide');
                            $('#payment-wizard').bootstrapWizard('next');
                        }
                    });

                    return false;
                });
            }
        });

        // SELECT PAYMENT METHOD
        $(document).on('click', '#payments-methods-list .method, #primary-payment-methods .method', function () {
            var method_item = $(this);
            gtag_vk_ret = 'pay_method';

            method_item.niftyOverlay({
                title: 'Подготовка к оплате',
                desc: 'Пожалуйста подождите...'
            });

            var operator = method_item.data('operator');
            var method = method_item.data('method');

            switch (operator) {
                case 'yandex':
                    method_item.niftyOverlay('show');
                    $('#operator-yandex > [name=cps_email]').val(invoice_details.email);
                    $('#operator-yandex > [name=paymentType]').val(method);
                    $('#operator-yandex').submit();
                    break;
                case 'paypal':
                    method_item.niftyOverlay('show');
                    $('#operator-paypal').submit();
                    break;
                case 'rbk':
                    method_item.niftyOverlay('show');
                    $('#operator-rbk > [name=user_email]').val(invoice_details.email);
                    $('#operator-rbk > [name=preference]').val(method);
                    $('#operator-rbk').submit();
                    break;
                case '2checkout':
                    method_item.niftyOverlay('show');
                    $('#operator-2checkout').submit();
                    break;
                case 'qiwi':
                    $('#modal-qiwi-tel').modal();
                    break;
                case 'ukr-card':
                    $('#modal-ukr-card').modal();
                    break;
                case 'bank':
                    method_item.niftyOverlay('show');
                    document.location.href = '<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/operators/bank/' + method + '?invoice=<?= APP::Module('Routing')->get['invoice_id'] ?>';
                    break;
                case 'z-payment':
                    method_item.niftyOverlay('show');
                    $('#operator-z-payment').submit();
                    break;
                case 'robokassa':
                    method_item.niftyOverlay('show');
                    $('#operator-robokassa').submit();
                    break;
                case 'w1':
                    method_item.niftyOverlay('show');
                    $('#operator-w1').submit();
                    break;
                case 'wm':
                    method_item.niftyOverlay('show');
                    $('#operator-wm').submit();
                    break;
            }
        });

        $('#set-qiwi-tel').click(function() {
            $('#modal-qiwi-tel').modal('hide');

            $.post('<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/operators/qiwi/create-bill', {
                'tel': $('#qiwi-tel').val(),
                'amount': '<?= $data['invoice']['amount'] ?>',
                'extras': {
                    'uid': '<?= $data['invoice']['user_id'] ?>',
                    'oid': '<?= $data['invoice']['id'] ?>'
                }
            }, function(ret) {
                switch (ret.status) {
                    case 'success':
                        document.location.href = 'https://w.qiwi.com/order/external/main.action?shop=249687&transaction=' + ret.transaction + '&successUrl=<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/success/<?= APP::Module('Routing')->get['invoice_id'] ?>&failUrl=<?php //echo APP::Module('Routing')->root . APP::Module('Billing')->uri; ?>billing/payments/fail/<?= APP::Module('Routing')->get['invoice_id'] ?>';
                        return false;
                        break;
                    case 'error':
                        switch (ret.info) {
                            case 'bill': alert('Невозможно выполнить оплату через QIWI. Обратитесь в службу поддержки support@glamurnenko.ru или попробуйте позже.'); break;
                            case 'tel': alert('Неверный номер телефона. Укажите номер телефона в международном формате (например: +79121234567)'); break;
                        }
                        return false;
                        break;
                }
            });
        });

        function find(array, value) {
            if (array.indexOf) {
                return array.indexOf(value);
            }

            for (var i = 0; i < array.length; i++) {
                if (array[i] === value) return i;
            }

            return -1;
        }

        var widget_id = '125500';
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id;
        var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);
        </script>

        <script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=OusM2I7DeamJcNggica4sL2*a5RqV02BtbALOaOtdJbLjj7vuRp*Kgzo1FT5KkH7k/GpbStIDfGob7LMv*S0HH9RnmdTfnqqATCnrSBqznPoqW*Af78FnHSBQjknJ4u81SMorniJ1oaGmI7ZtgJY5GjUjyyX2aE99B4SnqY83zE-';</script>

        <? //require_once '/home/admin/domains/glamurnenko.ru/public_html/pages/kods_full.php'; ?>
    </body>
</html>