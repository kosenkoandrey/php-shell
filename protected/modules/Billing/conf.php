<?

return [
    'routes' => [
        ['admin\/billing\/products(\?.*)?',                             'Billing', 'ManageProducts'],
        ['admin\/billing\/products\/add(\?.*)?',                        'Billing', 'AddProduct'],
        ['admin\/billing\/products\/edit\/(?P<product_id_hash>.*)',     'Billing', 'EditProduct'],
        
        ['admin\/billing\/invoices(\?.*)?',                             'Billing', 'ManageInvoices'],
        ['admin\/billing\/invoices\/add(\?.*)?',                        'Billing', 'AddInvoice'],
        ['admin\/billing\/invoices\/edit\/(?P<invoice_id_hash>.*)',     'Billing', 'EditInvoice'],
        
        ['billing\/payments\/make\/(?P<invoice_id>.*)',                 'Billing', 'PaymentMake'],
        ['admin\/billing\/settings(\?.*)?',                             'Billing', 'Settings'],
        // API
        ['admin\/billing\/products\/api\/search\.json(\?.*)?',          'Billing', 'APISearchProducts'],
        ['admin\/billing\/products\/api\/action\.json(\?.*)?',          'Billing', 'APISearchProductsAction'],
        ['admin\/billing\/products\/api\/add\.json(\?.*)?',             'Billing', 'APIAddProduct'],
        ['admin\/billing\/products\/api\/update\.json(\?.*)?',          'Billing', 'APIUpdateProduct'],
        ['admin\/billing\/products\/api\/remove\.json(\?.*)?',          'Billing', 'APIRemoveProduct'],
        
        ['admin\/billing\/invoices\/api\/search\.json(\?.*)?',          'Billing', 'APISearchInvoices'],
        ['admin\/billing\/invoices\/api\/action\.json(\?.*)?',          'Billing', 'APISearchInvoicesAction'],
        ['admin\/billing\/invoices\/api\/add\.json(\?.*)?',             'Billing', 'APIAddInvoice'],
        ['admin\/billing\/invoices\/api\/update\.json(\?.*)?',          'Billing', 'APIUpdateInvoice'],
        ['admin\/billing\/invoices\/api\/remove\.json(\?.*)?',          'Billing', 'APIRemoveInvoice'],
        
        ['admin\/billing\/invoices\/api\/details\/update.json(\?.*)?',  'Billing', 'APIUpdateInvoicesDetails'],
        
        ['admin\/billing\/api\/settings\/update\.json(\?.*)?',          'Billing', 'APIUpdateSettings']
    ]
];
