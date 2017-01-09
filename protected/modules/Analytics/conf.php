<?
return [
    'routes' => [
        ['admin\/analytics\/settings(\?.*)?',                       'Analytics', 'Settings'],
        
        ['admin\/analytics\/yandex\/get(\?.*)?',                    'Analytics', 'GetYandex'],
        ['admin\/analytics\/yandex\/token(\?.*)?',                  'Analytics', 'GetYandexToken'],
        
        //API
        ['admin\/analytics\/api\/dashboard\.json(\?.*)?',           'Analytics', 'APIDashboard'],
        ['admin\/analytics\/api\/settings\/update\.json(\?.*)?',    'Analytics', 'APIUpdateSettings'],
    ]
];
