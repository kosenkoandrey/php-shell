<?
return [
    'routes' => [
        ['admin\/sessions',                                        'Sessions', 'Settings'],           // Sessions settings
        ['admin\/sessions\/api\/settings\/update\.json(\?.*)?',    'Sessions', 'APIUpdateSettings']   // [API] Update sessions settings
    ],
    'init' => true
];