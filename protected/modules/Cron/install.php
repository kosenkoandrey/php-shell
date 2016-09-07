<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'cron_tmp_file':
            $_SESSION['core']['install']['cron']['tmp_file'] = $_POST['tmp_file'];
            break;
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Cron</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Cron</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['cron']['tmp_file'])) {
            $error = true;
            ?>
            <form method="post">
                <input type="hidden" name="action" value="cron_tmp_file">
                <label for="tmp_file">Temp file</label>
                <br>
                <input type="text" name="tmp_file" value="/tmp/crontab">
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    ?>
</body>
</html>
<?
$content = ob_get_contents();
ob_end_clean();

if ($error) {
    echo $content;
    exit;
}

// Install module

$data->extractTo(ROOT);

if (!APP::Module('Registry')->Get('module_cron_tmp_file')) {
    APP::Module('Registry')->Add('module_cron_tmp_file', $_SESSION['core']['install']['cron']['tmp_file']);
}

// Add triggers support
APP::Module('Triggers')->Register('add_cron_job', 'Cron', 'Add job');
APP::Module('Triggers')->Register('update_cron_job', 'Cron', 'Update job');
APP::Module('Triggers')->Register('remove_cron_job', 'Cron', 'Remove job');
APP::Module('Triggers')->Register('update_cron_settings', 'Cron', 'Update settings');