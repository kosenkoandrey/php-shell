<?

return [
    'routes' => [
        ['products\/garderob100\/form(\?.*)?', 'Pages', 'ProductGarderob100Form'],
        ['products\/garderob100\/(?P<token>.*)', 'Pages', 'ProductGarderob100Sale'],
    ]
];
