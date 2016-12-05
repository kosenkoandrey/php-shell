<?
switch (APP::Module('Routing')->get['version']) {
    case 'html': echo $data; break;
    case 'plaintext': ?><pre><?= $data ?></pre><? break;
}