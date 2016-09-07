<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Logs</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.min.css" rel="stylesheet">

        <style>
            #logs-table-header .actionBar .actions > button {
                display: none;
            }
        </style>
        
        <? APP::Render('core/widgets/css') ?>
    </head>
    <body data-ma-header="teal">
        <? 
        APP::Render('admin/widgets/header', 'include', [
            'Logs' => 'admin/logs',
            basename($data[0]) => 'admin/logs/view/' . APP::Module('Routing')->get['filename_hash']
        ]);
        ?>
        <section id="main">
            <? APP::Render('admin/widgets/sidebar') ?>

            <section id="content">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h2>View</h2>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-vmiddle">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">Date</th>
                                        <th style="width: 10%">Code</th>
                                        <th style="width: 80%">Error</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?
                                    foreach (array_reverse($data[1]) as $index => $str) {
                                        $error = json_decode($str);
                                        ?>
                                        <tr>
                                            <td><?= $error[0] ?></td>
                                            <td><?= $error[1] ?></td>
                                            <?
                                            switch ($error[1]) {
                                                case 0:
                                                    ?>
                                                    <td>
                                                        <a href="javascript:void(0)" onclick="toggle_visibility('error-<?= $index ?>');"><?= $error[2][1] ?></a>
                                                        <table class="table table-bordered" id="error-<?= $index ?>" style="display: none; width: 100%; margin-top: 10px;">
                                                            <tr>
                                                                <td>Code</td>
                                                                <td><?= $error[2][0] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Message</td>
                                                                <td><?= $error[2][1] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>File</td>
                                                                <td><?= $error[2][2] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Line</td>
                                                                <td><?= $error[2][3] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Context</td>
                                                                <td><pre><? print_r($error[2][4]) ?></pre></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Trace</td>
                                                                <td><pre><? print_r($error[2][5]) ?></pre></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <?
                                                    break;
                                                case 1:
                                                    ?>
                                                    <td>
                                                        <a href="javascript:void(0)" onclick="toggle_visibility('error-<?= $index ?>');"><?= $error[2][0] ?></a>
                                                        <table class="table table-bordered" id="error-<?= $index ?>" style="display: none; width: 100%; margin-top: 10px;">
                                                            <tr>
                                                                <td>Message</td>
                                                                <td><?= $error[2][0] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Code</td>
                                                                <td><?= $error[2][1] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>File</td>
                                                                <td><?= $error[2][2] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Line</td>
                                                                <td><?= $error[2][3] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Trace</td>
                                                                <td><pre><? print_r($error[2][4]) ?></pre></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <?
                                                    break;
                                                case 2:
                                                    ?>
                                                    <td>
                                                        <a href="javascript:void(0)" onclick="toggle_visibility('error-<?= $index ?>');"><?= $error[2]->message ?></a>
                                                        <table class="table table-bordered" id="error-<?= $index ?>" style="display: none; width: 100%; margin-top: 10px;">
                                                            <tr>
                                                                <td>Type</td>
                                                                <td><?= $error[2]->type ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Message</td>
                                                                <td><?= $error[2]->message ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>File</td>
                                                                <td><?= $error[2]->file ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Line</td>
                                                                <td><?= $error[2]->type ?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <?
                                                    break;
                                                default: ?><td><pre><? print_r(json_decode($str)) ?></pre></td><? break;
                                            }
                                            ?>
                                        </tr>
                                        <?
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
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
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bootgrid/jquery.bootgrid.updated.min.js"></script>

        <? APP::Render('core/widgets/js') ?>
        
        <script type="text/javascript">
            function toggle_visibility(id) {
                var e = document.getElementById(id);

                if (e.style.display == 'block') {
                   e.style.display = 'none';
                } else {
                   e.style.display = 'block';
                }
            }
        </script>
    </body>
</html>