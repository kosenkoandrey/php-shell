<?
return [
    'routes' => [
        ['admin\/triggers(\?.*)?',                              'Triggers', 'Manage'],     // Manage triggers
        ['admin\/triggers\/add',                                'Triggers', 'Add'],        // Add trigger
        ['admin\/triggers\/edit\/(?P<trigger_id_hash>.*)',      'Triggers', 'Edit'],       // Edit trigger
        
        ['admin\/triggers\/api\/list\.json(\?.*)?',             'Triggers', 'APIList'],    // [API] List triggers
        ['admin\/triggers\/api\/add\.json(\?.*)?',              'Triggers', 'APIAdd'],     // [API] Add trigger
        ['admin\/triggers\/api\/update\.json(\?.*)?',           'Triggers', 'APIUpdate'],  // [API] Update trigger
        ['admin\/triggers\/api\/remove\.json(\?.*)?',           'Triggers', 'APIRemove'],  // [API] Remove trigger
    ]
];