<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'crypt_set_key':
            $_SESSION['core']['install']['crypt']['key'] = $_POST['key'];
            break;
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Crypt</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Crypt</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['crypt']['key'])) {
            $error = true;
            ?>
            <form method="post">
                <input type="hidden" name="action" value="crypt_set_key">
                <label for="key">Key</label>
                <br>
                <input type="text" name="key" value="<?= str_pad(bin2hex(openssl_random_pseudo_bytes(16)), 32, "\0") ?>">
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

if (!APP::Module('Registry')->Get('module_crypt_key')) {
    APP::Module('Registry')->Add('module_crypt_key', $_SESSION['core']['install']['crypt']['key']);
}