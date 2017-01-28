<?

return [
    'routes' => [
        ['admin\/groups(\?.*)?',                                                        'Groups', 'ManageGroups'],
        ['admin\/groups\/add(\?.*)?',                                                   'Groups', 'AddGroup'],
        ['admin\/groups\/edit\/(?P<group_id_hash>.*)',                                  'Groups', 'EditGroup'],
        
        ['admin\/groups\/users\/add\/(?P<group_id_hash>.*)',                            'Groups', 'AddUser'],
        ['admin\/groups\/users\/(?P<group_id_hash>.*)',                                 'Groups', 'Users'],

        ['admin\/groups\/api\/list.json(\?.*)?',                                        'Groups', 'APIGroupsList'],
        ['admin\/groups\/api\/create\.json(\?.*)?',                                     'Groups', 'APICreateGroup'],
        ['admin\/groups\/api\/update\.json(\?.*)?',                                     'Groups', 'APIUpdateGroup'],
        ['admin\/groups\/api\/remove\.json(\?.*)?',                                     'Groups', 'APIRemoveGroup'],
        
        ['admin\/groups\/api\/users\/list.json(\?.*)?',                                 'Groups', 'APIUsersList'],
        ['admin\/groups\/api\/users\/create\.json(\?.*)?',                              'Groups', 'APIAddUser'],
        ['admin\/groups\/api\/users\/remove\.json(\?.*)?',                              'Groups', 'APIRemoveUser'],
    ]
];