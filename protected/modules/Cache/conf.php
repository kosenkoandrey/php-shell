<?
return [
    'routes' => [
        ['admin\/cache',                                        'Cache', 'Settings'],           // Cache settings
        ['admin\/cache\/api\/settings\/update\.json(\?.*)?',    'Cache', 'APIUpdateSettings']   // [API] Update cache settings
    ]
];