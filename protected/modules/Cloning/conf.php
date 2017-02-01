<?

return [
    'routes' => [
        ['admin\/cloning(\?.*)?',                     'Cloning', 'NewCloning'],
        ['admin\/cloning\/log(\?.*)?',                'Cloning', 'Log'],
        
        ['admin\/cloning\/api\/exec\.json(\?.*)?',    'Cloning', 'APICloning'],
    ],
    'profiles' => [
        'orekhov' => [
            'name' => 'Орехов', 
            'path' => '/var/www/domains/clients/pult.d-e-s-i-g-n.ru'
        ],
        'yurkovskaya' => [
            'name' => 'Юрковская', 
            'path' => '/var/www/domains/clients/pult.yurkovskaya.com'
        ],
        'demo' =>  [
            'name' => 'Демонстрация', 
            'path' => '/var/www/domains/demo.sendthis.ru'
        ]
    ]
];