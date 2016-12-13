<?
return [
    'routes' => [
        ['admin\/billing\/products(\?.*)?',                         'Billing', 'ManageProducts'],
        ['admin\/billing\/products\/add(\?.*)?',                    'Billing', 'AddProduct'],
        ['admin\/billing\/products\/edit\/(?P<product_id_hash>.*)', 'Billing', 'EditProduct'],
        
        ['admin\/billing\/settings(\?.*)?',                         'Billing', 'Settings'],

        // API
        ['admin\/billing\/products\/api\/search\.json(\?.*)?',      'Billing', 'APISearchProducts'],
        ['admin\/billing\/products\/api\/action\.json(\?.*)?',      'Billing', 'APISearchProductsAction'],
        ['admin\/billing\/products\/api\/add\.json(\?.*)?',         'Billing', 'APIAddProduct'],
        ['admin\/billing\/products\/api\/remove\.json(\?.*)?',      'Billing', 'APIRemoveProduct'],
        ['admin\/billing\/products\/api\/update\.json(\?.*)?',      'Billing', 'APIUpdateProduct'],
        
        ['admin\/billing\/api\/settings\/update\.json(\?.*)?',      'Billing', 'APIUpdateSettings'],
    ]
];