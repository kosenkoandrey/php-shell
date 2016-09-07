<?
return [
    'routes' => [
        ['admin\/ssh(\?.*)?',                               'SSH', 'ManageConnections'],    // Manage connections
        ['admin\/ssh\/add',                                 'SSH', 'AddConnection'],        // Add connection
        ['admin\/ssh\/edit\/(?P<connection_id_hash>.*)',    'SSH', 'EditConnection'],       // Edit connection
        
        ['admin\/ssh\/api\/list\.json(\?.*)?',              'SSH', 'APIListConnections'],   // [API] List connections
        ['admin\/ssh\/api\/add\.json(\?.*)?',               'SSH', 'APIAddConnection'],     // [API] Add connection
        ['admin\/ssh\/api\/update\.json(\?.*)?',            'SSH', 'APIUpdateConnection'],  // [API] Update connection
        ['admin\/ssh\/api\/remove\.json(\?.*)?',            'SSH', 'APIRemoveConnection'],  // [API] Remove connection
    ]
];