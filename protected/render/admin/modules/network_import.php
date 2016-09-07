<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Import modules via network</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        
        <? APP::Render('core/widgets/css') ?>
        
        <style>
            #modules > div {
                margin-bottom: 20px;
                padding: 20px;
                background: #fafafa;
            }
            #modules > div.active {
                background: #fff5b0;
            }
            #modules > div.installed {
                background: #f1f1f1;
            }

            #modules > div > a {
                font-size: 18px;
                margin-bottom: 5px;
                display: inline-block;
                font-weight: bold;
                color: black;
            }

            #modules > div > .dependencies {
                color: #bd2929;
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 5px;
            }
        </style>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Import modules via network' => 'admin/modules/import/network'
        ]);
        ?>
        
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <?
                        switch ($data['action']) {
                            case 'set_server':
                                ?>
                                <form method="post" class="form-horizontal" role="form">
                                    <input type="hidden" name="action" value="set_server">
                                    
                                    <div class="card-header">
                                        <h2>Server settings</h2>
                                    </div>
                                    
                                    <div class="card-body card-padding">
                                        <div class="form-group">
                                            <label for="server" class="col-sm-2 control-label">URL</label>
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <input type="text" name="server" value="https://php-shell.com" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-5">
                                                <button type="submit" class="btn palette-Teal bg waves-effect btn-lg">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?
                                break;
                            case 'select_modules':
                                ?>
                                <form id="select_modules" method="post" style="display: none">
                                    <input type="hidden" name="action" value="select_modules">
                                    <input type="hidden" name="modules" value="">
                                </form>
                                <form id="reset_server" method="post" style="display: none">
                                    <input type="hidden" name="action" value="reset_server">
                                </form>
                        
                                <div class="card-header">
                                    <h2>Available modules</h2>
                                    <ul class="actions">
                                        <li class="dropdown">
                                            <a href="javascript:void(0)" data-toggle="dropdown">
                                                <i class="zmdi zmdi-more-vert"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="javascript:void(0)" onclick="$('#select_modules').submit()">Import modules</a></li>
                                                <li><a href="javascript:void(0)" onclick="$('#reset_server').submit()">Server settings</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body card-padding">
                                    <div id="modules"></div>
                                </div>
                                <?
                                break;
                            case 'selected_modules':
                                ?>
                                <form id="import_modules" method="post" style="display: none">
                                    <input type="hidden" name="action" value="import_modules">
                                    <input type="hidden" name="modules" value="">
                                </form>
                                <form id="reset_modules" method="post" style="display: none">
                                    <input type="hidden" name="action" value="reset_modules">
                                </form>
                                <?
                                if ($_SESSION['core']['import']['modules']) {
                                    ?>
                                    <div class="card-header">
                                        <h2>Do you want to import the selected modules?</h2>
                                    </div>
                                    <div class="card-body card-padding">
                                        <p><?= $_SESSION['core']['import']['modules'] ?></p>
                                        <div class="btn-group btn-group-lg" role="group">
                                            <button onclick="$('#import_modules').submit()" class="btn palette-Teal bg waves-effect btn-lg">Yes</button> 
                                            <button onclick="$('#reset_modules').submit()" class="btn btn-default bg waves-effect btn-lg">No</button>
                                        </div>
                                    </div>
                                    <?
                                } else {
                                    ?>
                                    <div class="card-header">
                                        <h2>Modules not selected</h2>
                                    </div>
                                    <div class="card-body card-padding">
                                        <button onclick="$('#reset_modules').submit()" class="btn palette-Teal bg waves-effect btn-lg">Back</button>
                                    </div>
                                    <?
                                }
                                break;
                            case 'import_modules':
                                ?>
                                <div class="card-header">
                                    <h2>Selected modules has been imported</h2>
                                </div>
                                <div class="card-body card-padding">
                                    <p><?= $_SESSION['core']['import']['modules'] ?></p>
                                    <a href="<?= APP::Module('Routing')->root ?>admin/modules/import/install" class="btn palette-Teal bg waves-effect btn-lg">Install imported modules</a>
                                </div>
                                <?
                                break;
                        }
                        ?>
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

        <script>
            <?
            if ($data['action'] == 'select_modules') {
                ?>
                var modules = {};
                var installed_modules = <?= json_encode($data['installed_modules']) ?>;

                function TogleModule(module) {
                    switch ($('#modules > [data-id="' + module[0] + '"]').attr('class')) {
                        case 'card inactive':
                            $('#modules > [data-id="' + module[0] + '"]').removeClass('inactive').addClass('active');
                            $('#modules > [data-id="' + module[0] + '"] > button').html('Remove');
                            modules[module[0]][0] = true;

                            if (modules[module[0]][1]) {
                                $.each(modules[module[0]][1], function(key, value) {
                                    if ($.inArray(value, installed_modules) !== -1) return;
                                    $('#modules > [data-id="' + value + '"]').removeClass('inactive').addClass('active');
                                    $('#modules > [data-id="' + value + '"] > button').html('Remove');
                                    modules[value][0] = true;
                                });
                            }
                            break;
                        case 'card active':
                            $('#modules > [data-id="' + module[0] + '"]').removeClass('active').addClass('inactive');
                            $('#modules > [data-id="' + module[0] + '"] > button').html('Add');
                            modules[module[0]][0] = false;
                            break;
                    }

                    var selected_modules = [];

                    $.each(modules, function(key, value) {
                        if (value[0]) selected_modules.push(key);
                    });

                    $('#select_modules > [name="modules"]').val(selected_modules.join(' '));
                }

                function GetModulesList() {
                    $('#modules').html('Loading list of available modules. Please wait...');

                    $.post('<?= $_SESSION['core']['import']['server'] ?>/api/modules/list', function(result) {
                        $('#modules').empty();

                        $.each(result, function(key, module) {
                            modules[module[0]] = [false, module[2]];

                            $('#modules')
                            .append(
                                $('<div/>', {
                                    'data-id': module[0],
                                    class: 'card inactive'
                                })
                                .append(
                                    $('<a/>', {
                                        href: 'https://github.com/evildevel/php-shell/tree/master/protected/modules/' + module[0],
                                        target: '_blank'
                                    }).append(module[0])
                                )
                                .append(
                                    $('<div/>', {
                                        class: 'description'
                                    })
                                    .css('white-space', 'pre-wrap')
                                    .append(module[1])
                                )
                                .append(
                                    $('<div/>', {
                                        class: 'dependencies'
                                    })
                                    .append(module[2] ? '<i class="zmdi zmdi-attachment"></i> ' + module[2].join(' / ') : '')
                                )
                                .append(
                                    $('<button/>', {
                                        type: 'button',
                                        class: 'btn btn-default'
                                    })
                                    .append('Add')
                                    .on('click', function() {
                                        TogleModule(module);
                                    })
                                )
                            );

                            if ($.inArray(module[0], installed_modules) !== -1) {
                                $('#modules > [data-id="' + module[0] + '"]').removeClass('inactive').addClass('installed');
                                $('#modules > [data-id="' + module[0] + '"] > button').html('Installed').attr('disabled', true);
                            }
                        });
                    });
                }

                $(function() {
                    GetModulesList();
                });
                <?
            }
            ?>
        </script>
        
        <? APP::Render('core/widgets/js') ?>
    </body>
</html>