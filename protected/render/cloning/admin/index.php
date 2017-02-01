<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Создание клона</title>

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
            'Клонирование' => 'admin/cloning'
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <form id="cloning" class="form-horizontal" role="form">
                            <div class="card-header">
                                <h2>Создание клона</h2>
                            </div>
                            <div class="card-body card-padding">
                                <div class="form-group">
                                    <label for="profile" class="col-sm-2 control-label">Профиль</label>
                                    <div class="col-sm-10">
                                        <select id="profile" name="profile" class="selectpicker">
                                            <? foreach (APP::Module('Cloning')->conf['profiles'] as $key => $value) { ?><option value="<?= $key ?>">[<?= $value['name'] ?>] <?= $value['path'] ?></option><? } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-5">
                                        <button type="submit" class="btn palette-Teal bg waves-effect btn-lg">Клонировать</button>
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
        
        <? APP::Render('core/widgets/js') ?>
        
        <script>
            $(document).ready(function() {
                $('#cloning').submit(function(event) {
                    event.preventDefault();

                    $(this).find('[type="submit"]').html('Подожите...').attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        url: '<?= APP::Module('Routing')->root ?>admin/cloning/api/exec.json',
                        data: $(this).serialize(),
                        success: function(result) {
                            switch(result.status) {
                                case 'success':
                                    swal({
                                        title: 'Готово!',
                                        text: 'Клонирование было выполнено успешно',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Ok',
                                        closeOnConfirm: false
                                    });
                                    break;
                                case 'error': break;
                            }

                            $('#cloning').find('[type="submit"]').html('Клонировать').attr('disabled', false);
                        }
                    });
                });
            });
        </script>
    </body>
</html>