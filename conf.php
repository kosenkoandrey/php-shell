<?
define('DEFAULT_DOMAIN', '');

return [
    'location'          => ['http', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : DEFAULT_DOMAIN, '/'],
    'encoding'          => 'UTF-8',
    'locale'            => 'en_US',
    'timezone'          => 'Etc/GMT-3',
    'memory_limit'      => '512M',
    'error_reporting'   => E_ALL,
    'debug'             => true,
    'install'           => false,
    'logs'              => ROOT . '/logs',
];
