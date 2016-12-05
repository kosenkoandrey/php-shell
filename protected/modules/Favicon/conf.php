<?
return [
    'routes' => [
        ['favicon\.ico',                            'Favicon', 'OutMain'],
        ['(?P<brand>.*)\-icon\-(?P<size>.*)\.png',  'Favicon', 'OutBrand'],
        ['apple\-icon\.png',                        'Favicon', 'OutApple'],
        ['favicon\-(?P<size>.*)\.png',              'Favicon', 'OutFavicon'],
        ['manifest\.json',                          'Favicon', 'OutManifest'],
        ['browserconfig\.xml',                      'Favicon', 'OutBrowserconfig']
    ]
];