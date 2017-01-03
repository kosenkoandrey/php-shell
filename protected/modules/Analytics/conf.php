<?
return [
    'routes' => [
        ['admin\/analytics\/monitor\/business\/involvement(\?.*)?',     'Analytics', 'BusinessInvolvment'],
        ['admin\/analytics\/yandex\/updateyandexstats(\?.*)?',          'Analytics', 'UpdateYandexStats'],
        ['admin\/analytics\/yandex\/updatewebstats(\?.*)?',             'Analytics', 'UpdateWebStats'],
        ['admin\/analytics\/yandex\/settings(\?.*)?',                   'Analytics', 'YandexSettings'],
        ['admin\/analytics\/settings(\?.*)?',                           'Analytics', 'Settings'],
        //API
        ['admin\/analytics\/api\/dashboard\.json(\?.*)?',               'Analytics', 'APIDashboard'],
        ['admin\/analytics\/api\/yandex\/settings\/update\.json(\?.*)?','Analytics', 'APIUpdateYandexSettings'],
        ['admin\/analytics\/api\/settings\/update\.json(\?.*)?',        'Analytics', 'APIUpdateSettings'],
    ]
];
