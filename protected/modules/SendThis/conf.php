<?
return [
    'routes' => [
        ['sendthis\/api\/webhooks\.json(\?.*)?',                   'SendThis', 'Webhooks'],           // SendThis webhooks handler
        
        ['admin\/sendthis',                                        'SendThis', 'Settings'],           // SendThis settings
        ['admin\/sendthis\/api\/settings\/update\.json(\?.*)?',    'SendThis', 'APIUpdateSettings']   // [API] Update SendThis settings
    ]
];