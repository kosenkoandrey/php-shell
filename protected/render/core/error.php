<?
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 10');

switch ($data[0]) {
    case 0:     $title = 'PHP ErrorHandler';                break;
    case 1:     $title = 'PHP ExceptionHandler';            break;
    case 2:     $title = 'PHP ShutdownHandler';             break;
    case 3:     $title = 'Module class not found';          break;
    case 4:     $title = 'Invalid module class name';       break;
    case 5:     $title = 'Can\'t render file';              break;
    case 6:     $title = 'Module test class not found';     break;
    case 7:     $title = 'Invalid module test class name';  break;
    default:    $title = 'Unknown error';                   break;
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