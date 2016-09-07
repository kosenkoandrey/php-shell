<?
return [
    'routes' => [
        ['admin\/crypt',                                        'Crypt', 'Settings'],           // Crypt settings
        ['admin\/crypt\/api\/settings\/update\.json(\?.*)?',    'Crypt', 'APIUpdateSettings']   // [API] Update crypt settings
    ]
];