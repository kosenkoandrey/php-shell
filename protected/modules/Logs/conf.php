<?
return Array(
    'routes' => [
        ['admin\/logs',                                 'Logs', 'Manage'],          // Manage logs
        ['admin\/logs\/view\/(?P<filename_hash>.*)',    'Logs', 'View'],            // View log
        
        ['admin\/logs\/api\/list\.json(\?.*)?',         'Logs', 'APIListLogs'],     // [API] List logs
        ['admin\/logs\/api\/remove\.json(\?.*)?',       'Logs', 'APIRemoveLog'],    // [API] Remove log
    ]
);