<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'ssh_set_connection': $_SESSION['core']['install']['ssh']['connection'] = $_POST['connection']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install SSH</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install SSH</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['ssh']['connection'])) {
            $error = true;
            ?>
            <h3>Create connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="ssh_set_connection">
                
                <label id="connection_host" for="connection[host]">Host</label>
                <br>
                <input type="text" id="connection_host" name="connection[host]" value="127.0.0.1">
                <br><br>
                
                <label id="connection_port" for="connection[port]">Port</label>
                <br>
                <input type="text" id="connection_port" name="connection[port]" value="22">
                <br><br>
                
                <label id="connection_user" for="connection[user]">User</label>
                <br>
                <input type="text" id="connection_user" name="connection[user]">
                <br><br>
                
                <label id="connection_password" for="connection[password]">Password</label>
                <br>
                <input type="password" id="connection_password" name="connection[password]">
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

$input = $_SESSION['core']['install']['ssh'];

APP::Module('Registry')->Add('module_ssh_connection', json_encode([
    $input['connection']['host'], 
    $input['connection']['port'], 
    $input['connection']['user'], 
    APP::Module('Crypt')->Encode($input['connection']['password']
)]));

APP::Module('Triggers')->Register('add_ssh_connection', 'SSH', 'Add connection');
APP::Module('Triggers')->Register('remove_ssh_connection', 'SSH', 'Remove connection');
APP::Module('Triggers')->Register('update_ssh_connection', 'SSH', 'Update connection');