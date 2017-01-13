<?
return [
    'routes' => [
        ['admin\/tunnels(\?.*)?',                                   'Tunnels', 'ManageTunnels'],
        ['admin\/tunnels\/add(\?.*)?',                              'Tunnels', 'AddTunnel'],
        ['admin\/tunnels\/edit\/(?P<tunnel_id_hash>.*)',            'Tunnels', 'EditTunnel'],
        ['admin\/tunnels\/scheme\/(?P<tunnel_id_hash>.*)',          'Tunnels', 'TunnelScheme'],
        ['admin\/tunnels\/settings(\?.*)?',                         'Tunnels', 'Settings'],
        
        ['admin\/tunnels\/api\/dashboard\.json(\?.*)?',             'Tunnels', 'APIDashboard'],
        ['admin\/tunnels\/api\/search\.json(\?.*)?',                'Tunnels', 'APISearchTunnels'],
        ['admin\/tunnels\/api\/action\.json(\?.*)?',                'Tunnels', 'APISearchAction'],
        ['admin\/tunnels\/api\/add\.json(\?.*)?',                   'Tunnels', 'APIAddTunnel'],
        ['admin\/tunnels\/api\/remove\.json(\?.*)?',                'Tunnels', 'APIRemoveTunnel'],
        ['admin\/tunnels\/api\/update\.json(\?.*)?',                'Tunnels', 'APIUpdateTunnel'],
        ['admin\/tunnels\/api\/settings\/update\.json(\?.*)?',      'Tunnels', 'APIUpdateSettings'],
        
        ['admin\/tunnels\/api\/scheme\.json(\?.*)?',                'Tunnels', 'APIScheme'],
        
        ['admin\/tunnels\/api\/actions\/create\.json(\?.*)?',       'Tunnels', 'APICreateAction'],
        ['admin\/tunnels\/api\/actions\/update\.json(\?.*)?',       'Tunnels', 'APIUpdateAction'],
        ['admin\/tunnels\/api\/actions\/remove\.json(\?.*)?',       'Tunnels', 'APIRemoveAction'],
        
        ['admin\/tunnels\/api\/conditions\/create\.json(\?.*)?',    'Tunnels', 'APICreateCondition'],
        ['admin\/tunnels\/api\/conditions\/update\.json(\?.*)?',    'Tunnels', 'APIUpdateCondition'],
        ['admin\/tunnels\/api\/conditions\/remove\.json(\?.*)?',    'Tunnels', 'APIRemoveCondition'],
        
        ['admin\/tunnels\/api\/timeouts\/create\.json(\?.*)?',      'Tunnels', 'APICreateTimeout'],
        ['admin\/tunnels\/api\/timeouts\/update\.json(\?.*)?',      'Tunnels', 'APIUpdateTimeout'],
        ['admin\/tunnels\/api\/timeouts\/remove\.json(\?.*)?',      'Tunnels', 'APIRemoveTimeout'],
        
        ['tunnels\/api\/subscribe\.json(\?.*)?',                    'Tunnels', 'APISubscribe'],
        
        ['tunnels\/exec(\?.*)?',                                    'Tunnels', 'Exec'],
        ['tunnels\/queue(\?.*)?',                                   'Tunnels', 'Queue'],
        ['tunnels\/timer\/(?P<input>.*)',                           'Tunnels', 'Timer'],
        ['tunnels\/unsubscribe\/(?P<user_tunnel_hash>.*)',          'Tunnels', 'Unsubscribe'],
        
        ['admin\/tunnels\/test\/subscribe(\?.*)?',                  'Tunnels', 'TestSubscribe'],   
    ],
    'init' => true
];