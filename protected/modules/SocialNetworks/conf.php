<?
return [
    'routes' => [
        ['admin\/social_networks\/settings(\?.*)?',                     'SocialNetworks', 'Settings'],          // Social networks settings
        ['admin\/social_networks\/other(\?.*)?',                        'SocialNetworks', 'Other'],             // Other social networks settings
        ['admin\/social_networks(\?.*)?',                               'SocialNetworks', 'Overview'],          // Overview

        // API
        
        ['admin\/social_networks\/api\/settings\/update\.json(\?.*)?',  'SocialNetworks', 'APIUpdateSettings'], // [API] Update social networks settings
        ['admin\/social_networks\/api\/other\/update\.json(\?.*)?',     'SocialNetworks', 'APIUpdateOther'],    // [API] Update other social networks settings
    ]
];