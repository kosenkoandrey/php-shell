<?
$error = false;

if (isset($_GET['reset'])) {
    unset($_SESSION['core']['install']['taskmanager']);
}

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'taskmanager_set_db_connection':
            $_SESSION['core']['install']['taskmanager']['db_connection'] = $_POST['db_connection'];
            break;
        case 'taskmanager_set_ssh_connection':
            $_SESSION['core']['install']['taskmanager']['ssh_connection'] = $_POST['ssh_connection'];
            break;
        case 'taskmanager_set_settings':
            $_SESSION['core']['install']['taskmanager']['settings'] = $_POST['settings'];
            break;
    }
}
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP-shell - Install Task Manager</title>

        <!-- Vendor CSS -->
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet">        
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/google-material-color/dist/palette.css" rel="stylesheet">
        <link href="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">

        <? APP::Render('core/widgets/css') ?>

        <style>
            body:before {
                height: 139px !important;
            }

            #header {
                margin-bottom: 30px !important;
            }

            .main-menu > li > a:hover {
                cursor: default;
                background-color: transparent;
            }
        </style>
    </head>
    <body data-ma-header="teal">
        <header id="header" class="media">
            <div class="pull-left h-logo">
                <a href="<?= APP::Module('Routing')->root ?>" class="hidden-xs">
                    PHP-shell   
                    <small>MICRO FRAMEWORK</small>
                </a>
            </div>
            <div class="media-body h-nav m-t-5">
                <div class="btn-group btn-group-lg">
                    <a href="javascript:void(0)" class="btn btn-default">Install Task Manager</a>
                </div>
            </div>
        </header>
        <section id="main">
            <aside id="s-main-menu" class="sidebar">
                <div class="smm-header">
                    <i class="zmdi zmdi-long-arrow-left" data-ma-action="sidebar-close"></i>
                </div>
                <ul class="main-menu">
                    <li class="db_connection"><a href="javascript:void(0)"><i class="zmdi zmdi-square-o"></i> DB connection</a></li>
                    <li class="ssh_connection"><a href="javascript:void(0)"><i class="zmdi zmdi-square-o"></i> SSH connection</a></li>
                    <li class="settings"><a href="javascript:void(0)"><i class="zmdi zmdi-square-o"></i> Settings</a></li>
                </ul>
            </aside>
            <section id="content">
                <div class="container">
                    <div class="card">
                        <?
                        if (isset($_GET['debug'])) {
                            ?><pre><? print_r($_SESSION['core']['install']['taskmanager']) ?></pre><?
                        }

                        if (!$error) {
                            if (!isset($_SESSION['core']['install']['taskmanager']['db_connection'])) {
                                $error = true;
                                ?>
                                <div class="card-header">
                                    <h2>Select DB connection</h2>
                                </div>
                                <div class="card-body card-padding">
                                    <form method="post" class="form-horizontal" role="form">
                                        <input type="hidden" name="action" value="taskmanager_set_db_connection">

                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <select name="db_connection" class="selectpicker">
                                                        <? foreach (array_keys(APP::Module('DB')->conf['connections']) as $connection) { ?><option value="<?= $connection ?>"><?= $connection ?></option><? } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <button type="submit" class="btn palette-Teal bg waves-effect btn-lg">Next</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?
                            }
                        }
                        if (!$error) {
                            if (!isset($_SESSION['core']['install']['taskmanager']['ssh_connection'])) {
                                $error = true;

                                // Build SSH connections array
                                $ssh_connections = [];
                                $tmp_ssh_connections = APP::Module('Registry')->Get(['module_ssh_connection'], ['id', 'value']);
                                
                                foreach (array_key_exists('module_ssh_connection', $tmp_ssh_connections) ? (array) $tmp_ssh_connections['module_ssh_connection'] : [] as $connection) {
                                    $ssh_connection_value = json_decode($connection['value'], 1);
                                    $ssh_connections[$connection['id']] = $ssh_connection_value[2] . '@' . $ssh_connection_value[0] . ':' . $ssh_connection_value[1];
                                }
                                ?>
                                <div class="card-header">
                                    <h2>Select SSH connection</h2>
                                </div>
                                <div class="card-body card-padding">
                                    <form method="post" class="form-horizontal" role="form">
                                        <input type="hidden" name="action" value="taskmanager_set_ssh_connection">

                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <div class="fg-line">
                                                    <select name="ssh_connection" class="selectpicker">
                                                        <? foreach ($ssh_connections as $ssh_connection_id => $ssh_connection_name) { ?><option value="<?= $ssh_connection_id ?>"><?= $ssh_connection_name ?></option><? } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <button type="submit" class="btn palette-Teal bg waves-effect btn-lg">Next</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?
                            }
                        }
                        if (!$error) {
                            if (!isset($_SESSION['core']['install']['taskmanager']['settings'])) {
                                $error = true;
                                ?>
                                <div class="card-header">
                                    <h2>Settings</h2>
                                </div>
                                <div class="card-body card-padding">
                                    <form method="post" class="form-horizontal" role="form">
                                        <input type="hidden" name="action" value="taskmanager_set_settings">

                                        <div class="form-group">
                                            <label for="complete_lifetime" class="col-sm-2 control-label">Complete task lifetime</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="settings[complete_lifetime]" id="complete_lifetime" value="1 month">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="max_execution_time" class="col-sm-2 control-label">Max execution time</label>
                                            <div class="col-sm-1">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="settings[max_execution_time]" id="max_execution_time" value="72000">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="memory_limit" class="col-sm-2 control-label">Memory limit</label>
                                            <div class="col-sm-1">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="settings[memory_limit]" id="memory_limit" value="512M">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="tmp_dir" class="col-sm-2 control-label">Temp dir</label>
                                            <div class="col-sm-2">
                                                <div class="fg-line">
                                                    <input type="text" class="form-control" name="settings[tmp_dir]" id="memory_limit" value="/tmp">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-2">
                                                <button type="submit" class="btn palette-Teal bg waves-effect btn-lg">Next</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?
                            }
                        }
                        ?>     
                    </div>
                </div>
            </section>

            <footer id="footer">
                Copyright &copy; <?= date('Y') ?> php-shell

                <ul class="f-menu">
                    <li><a href="https://php-shell.com">Home</a></li>
                    <li><a href="https://php-shell.com/downloads">Downloads</a></li>
                    <li><a href="https://php-shell.com/license">License</a></li>
                </ul>
            </footer>
        </section>

        <? APP::Render('core/widgets/page_loader') ?>
        <? APP::Render('core/widgets/ie_warning') ?>

        <!-- Javascript Libraries -->
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?= APP::Module('Routing')->root ?>public/ui/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>

        <? APP::Render('core/widgets/js') ?>

        <script>
            <?
            if (isset($_SESSION['core']['install']['taskmanager']['db_connection'])) {
                ?>$('.main-menu > .db_connection i').removeClass('zmdi-square-o').addClass('zmdi-check-square');<?

                if (isset($_SESSION['core']['install']['taskmanager']['ssh_connection'])) {
                    ?>$('.main-menu > .ssh_connection i').removeClass('zmdi-square-o').addClass('zmdi-check-square');<?

                    if (isset($_SESSION['core']['install']['taskmanager']['settings'])) {
                        ?>$('.main-menu > .settings i').removeClass('zmdi-square-o').addClass('zmdi-check-square');<?
                    } else {
                        ?>$('.main-menu > .settings').addClass('active');<?
                    }
                } else {
                    ?>$('.main-menu > .ssh_connection').addClass('active');<?
                }
            } else {
                ?>$('.main-menu > .db_connection').addClass('active');<?
            }
            ?>
        </script>
    </body>
  </html>
<?
$content = ob_get_contents();
ob_end_clean();

if ($error) {
    echo $content;
    exit;
}

// Install module //////////////////////////////////////////////////////////////

$data->extractTo(ROOT);

APP::Module('DB')->Open($_SESSION['core']['install']['taskmanager']['db_connection'])->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `task_manager` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
      `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
      `method` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
      `args` text COLLATE utf8_unicode_ci NOT NULL,
      `state` enum('wait','complete') COLLATE utf8_unicode_ci NOT NULL,
      `cr_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `exec_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
      `complete_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


    ALTER TABLE `task_manager`
      ADD PRIMARY KEY (`id`),
      ADD KEY `state_exec_date` (`state`,`exec_date`) USING BTREE,
      ADD KEY `state_complete_date` (`state`,`complete_date`) USING BTREE;


    ALTER TABLE `task_manager`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
");

APP::Module('Registry')->Add('module_taskmanager_db_connection', $_SESSION['core']['install']['taskmanager']['db_connection']);
APP::Module('Registry')->Add('module_taskmanager_ssh_connection', $_SESSION['core']['install']['taskmanager']['ssh_connection']);
APP::Module('Registry')->Add('module_taskmanager_complete_lifetime', $_SESSION['core']['install']['taskmanager']['settings']['complete_lifetime']);
APP::Module('Registry')->Add('module_taskmanager_max_execution_time', $_SESSION['core']['install']['taskmanager']['settings']['max_execution_time']);
APP::Module('Registry')->Add('module_taskmanager_memory_limit', $_SESSION['core']['install']['taskmanager']['settings']['memory_limit']);
APP::Module('Registry')->Add('module_taskmanager_tmp_dir', $_SESSION['core']['install']['taskmanager']['settings']['tmp_dir']);

$ssh = $_SESSION['core']['install']['taskmanager']['ssh_connection'];

APP::Module('Cron')->Add($ssh, ['*/1', '*', '*', '*', '*', 'php ' . ROOT . '/init.php TaskManager Exec []']);
APP::Module('Cron')->Add($ssh, ['*/1', '*', '*', '*', '*', 'php ' . ROOT . '/init.php TaskManager GC []']);

APP::Module('Registry')->Add('module_trigger_type', '["taskmanager_add", "Task Manager", "Add task"]');
APP::Module('Registry')->Add('module_trigger_type', '["taskmanager_update", "Task Manager", "Update task"]');
APP::Module('Registry')->Add('module_trigger_type', '["taskmanager_remove", "Task Manager", "Remove task"]');
APP::Module('Registry')->Add('module_trigger_type', '["taskmanager_exec", "Task Manager", "Exec task"]');
APP::Module('Registry')->Add('module_trigger_type', '["taskmanager_update_settings", "Task Manager", "Update settings"]');