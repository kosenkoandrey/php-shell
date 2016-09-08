<?
ob_end_clean();

if (APP::$console) {
    echo "An error has occured!\n";
    echo "---------------------\n";

    switch ($data[0]) {
        case 0: echo $data[1][1]; break;
        case 1: echo $data[1][0]; break;
        case 2: echo $data[1]['message']; break;
    }
    
    echo "\n--------------------\n";
    echo "Please see log files " . APP::$conf['logs'] . "\n\n";
    exit;
}

header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 10');

switch ($data[0]) {
    case 0:     $title = 'PHP ErrorHandler';                break;
    case 1:     $title = 'PHP ExceptionHandler';            break;
    case 2:     $title = 'PHP ShutdownHandler';             break;
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Error</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
    <style>
        #circle {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: red;
            -moz-border-radius: 10px;
            -webkit-border-radius: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <?
    if (APP::$conf['debug']) {
        ?>
        <h1><div id="circle"></div> <?= $title ?></h1>
        <hr>
        <pre><? print_r($data); ?></pre>
        <?
    } else {
        ?>
        <h1>Service Temporarily Unavailable</h1>
        <hr>
        Please try again later
        <?
    }
    ?>
</body>
</html>
<?
exit;