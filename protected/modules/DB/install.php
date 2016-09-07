<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'db_create_connection': $_SESSION['core']['install']['db']['connection'] = $_POST['connection']; break;
        case 'db_remove_connection': unset($_SESSION['core']['install']['db']['connection']); break;
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install DB</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install DB</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['db']['connection'])) {
            $error = true;
            ?>
            <h3>Create connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="db_create_connection">
                <label for="connection[type]">Type</label>
                <br>
                <input type="text" name="connection[type]" value="mysql">
                <br><br>
                <label for="connection[host]">Host</label>
                <br>
                <input type="text" name="connection[host]" value="localhost">
                <br><br>
                <label for="connection[database]">Database</label>
                <br>
                <input type="text" name="connection[database]">
                <br><br>
                <label for="connection[username]">Username</label>
                <br>
                <input type="text" name="connection[username]">
                <br><br>
                <label for="connection[password]">Password</label>
                <br>
                <input type="password" name="connection[password]">
                <br><br>
                <label for="connection[charset]">Charset</label>
                <br>
                <input type="text" name="connection[charset]" value="utf8">
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    
    if (!$error) {
        try {
            new PDO($_SESSION['core']['install']['db']['connection']['type'] . ':host=' . $_SESSION['core']['install']['db']['connection']['host'] . ';dbname=' . $_SESSION['core']['install']['db']['connection']['database'], $_SESSION['core']['install']['db']['connection']['username'], $_SESSION['core']['install']['db']['connection']['password']);
        } catch (Exception $e) {
            $error = true;
            ?>
            <h3>Unable to connect: <?= $e->getMessage() ?></h3>
            <form method="post">
                <input type="hidden" name="action" value="db_remove_connection">
                <input type="submit" value="Back">
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
file_put_contents(ROOT . '/protected/modules/DB/conf.php', "<? return ['connections' => ['auto' => ['type' => '" . $_SESSION['core']['install']['db']['connection']['type'] . "', 'host' => '" . $_SESSION['core']['install']['db']['connection']['host'] . "', 'database' => '" . $_SESSION['core']['install']['db']['connection']['database'] . "', 'username' => '" . $_SESSION['core']['install']['db']['connection']['username'] . "', 'password'  => '" . $_SESSION['core']['install']['db']['connection']['password'] . "','charset' => '" . $_SESSION['core']['install']['db']['connection']['charset'] . "']], 'init' => true];");