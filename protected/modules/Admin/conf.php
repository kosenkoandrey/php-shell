<?
return [
    'routes' => [
        ['admin(\?.*)?',                                           'Admin', 'Manage'],
        ['admin\/modules\/export\/(?P<module_hash>.*)',            'Admin', 'ExportModule'],
        ['admin\/modules\/import',                                 'Admin', 'ImportModules'],
        ['admin\/modules\/import\/network',                        'Admin', 'NetworkImportModules'],
        ['admin\/modules\/import\/remove\/(?P<module_path>.*)',    'Admin', 'RemoveImportedModule'],
        ['admin\/modules\/import\/install',                        'Admin', 'InstallImportedModules'],
        ['admin\/modules\/uninstall\/(?P<module_hash>.*)',         'Admin', 'UninstallModule'],
        
        ['admin\/api\/modules\/uninstall\/(?P<module_hash>.*)',    'Admin', 'APIUninstallModule'],
    ]
];