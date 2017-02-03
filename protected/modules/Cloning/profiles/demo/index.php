<?
exec('mkdir -m 755 ' . $dist . '/logs');

exec('cp -R ' . ROOT . '/protected ' . $dist . '/protected');
exec('cp -R ' . ROOT . '/public ' . $dist . '/public');

exec('cp ' . ROOT . '/app.php ' . $dist . '/app.php');
exec('cp ' . ROOT . '/conf.php ' . $dist . '/conf.php');
exec('cp ' . ROOT . '/init.php ' . $dist . '/init.php');
exec('cp ' . ROOT . '/phpunit.phar ' . $dist . '/phpunit.phar');