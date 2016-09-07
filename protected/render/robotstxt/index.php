<?
header('Content-Type: text/plain');
header('Cache-Control: private');
?>
Host: <?= APP::$conf['location'][1] ?>

User-agent: *
Disallow: /