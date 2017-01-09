<?
return [
    'routes' => [
        ['members\/pages\/(?P<group_sub_id_hash>.*)',                                               'Members', 'ManageUserPages'],  // Manage user pages
        ['members\/page\/(?P<page_id_hash>.*)',                                                     'Members', 'ViewPage'],         // View page
        
        ['admin\/members\/pages\/(?P<group_sub_id_hash>.*)\/groups\/add',                           'Members', 'AddPagesGroup'],    // Add pages group
        ['admin\/members\/pages\/(?P<group_sub_id_hash>.*)\/groups\/(?P<group_id_hash>.*)\/edit',   'Members', 'EditPagesGroup'],   // Edit pages group
        ['admin\/members\/pages\/(?P<group_sub_id_hash>.*)\/add(\?.*)?',                            'Members', 'AddPage'],          // Add page
        ['admin\/members\/pages\/(?P<group_sub_id_hash>.*)\/edit\/(?P<page_id_hash>.*)',            'Members', 'EditPage'],         // Edit page
        ['admin\/members\/pages\/(?P<group_sub_id_hash>.*)',                                        'Members', 'ManagePages'],      // Manage pages
       
        ['admin\/members\/settings(\?.*)?',                                                         'Members', 'Settings'],         // Members settings
        
        // API
        
        ['admin\/members\/api\/pages\/add\.json(\?.*)?',                 'Members', 'APIAddPage'],            // [API] Add page
        ['admin\/members\/api\/pages\/remove\.json(\?.*)?',              'Members', 'APIRemovePage'],         // [API] Remove page
        ['admin\/members\/api\/pages\/update\.json(\?.*)?',              'Members', 'APIUpdatePage'],         // [API] Update page
        ['admin\/members\/api\/pages\/groups\/add\.json(\?.*)?',         'Members', 'APIAddPagesGroup'],      // [API] Add pages group
        ['admin\/members\/api\/pages\/groups\/remove\.json(\?.*)?',      'Members', 'APIRemovePagesGroup'],   // [API] Remove pages group
        ['admin\/members\/api\/pages\/groups\/update\.json(\?.*)?',      'Members', 'APIUpdatePagesGroup'],   // [API] Update pages group
        ['admin\/members\/api\/pages\/get\.json(\?.*)?',                 'Members', 'APIGetPages'],           // [API] Get page
        
        
        ['admin\/members\/api\/settings\/update\.json(\?.*)?',           'Members', 'APIUpdateSettings'],   // [API] Update members settings
    ]
];