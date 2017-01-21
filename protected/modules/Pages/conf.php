<?

return [
    'routes' => [
        ['products\/garderob100\/form(\?.*)?',      'Pages', 'ProductGarderob100Form'],
        ['products\/garderob100\/(?P<token>.*)',    'Pages', 'ProductGarderob100Sale'],
        ['products\/bigcolor\/form(\?.*)?',         'Pages', 'ProductBigColorForm'],
        ['products\/bigcolor\/activation(\?.*)?',   'Pages', 'ProductBigColorActivation'],
        ['products\/bigcolor\/(?P<token>.*)',       'Pages', 'ProductBigColorSale'],
        ['products\/101office\/form(\?.*)?',        'Pages', 'Product101OfficeForm'],
        ['products\/101office\/activation(\?.*)?',  'Pages', 'Product101OfficeActivation'],
        ['products\/101office\/(?P<token>.*)',      'Pages', 'Product101OfficeSale'],
    ]
];
