<?
return [
    'routes' => [
        ['admin\/costs(\?.*)?',                                 'Costs', 'ManageCosts'],
        ['admin\/costs\/add(\?.*)?',                            'Costs', 'AddCost'],
        ['admin\/costs\/edit\/(?P<cost_id_hash>.*)',            'Costs', 'EditCost'],
        ['admin\/costs\/settings(\?.*)?',                       'Costs', 'Settings'],
        
        ['admin\/costs\/yandex\/get(\?.*)?',                    'Costs', 'GetYandex'],
        ['admin\/costs\/yandex\/token(\?.*)?',                  'Costs', 'GetYandexToken'],

        // API
        ['admin\/costs\/api\/dashboard\.json(\?.*)?',           'Costs', 'APIDashboard'],
        ['admin\/costs\/api\/search\.json(\?.*)?',              'Costs', 'APISearchCosts'],
        ['admin\/costs\/api\/action\.json(\?.*)?',              'Costs', 'APISearchAction'],
        ['admin\/costs\/api\/add\.json(\?.*)?',                 'Costs', 'APIAddCost'],
        ['admin\/costs\/api\/remove\.json(\?.*)?',              'Costs', 'APIRemoveCost'],
        ['admin\/costs\/api\/update\.json(\?.*)?',              'Costs', 'APIUpdateCost'],
        ['admin\/costs\/api\/settings\/update\.json(\?.*)?',    'Costs', 'APIUpdateSettings'],
    ]
];