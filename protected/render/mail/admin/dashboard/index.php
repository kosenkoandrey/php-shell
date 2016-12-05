<?
$card = 'Mail';

$tabs = [
    'report' => 'Report',
    'fbl' => 'FBL',
    'log' => 'Log'
];

$out = [];

foreach ($tabs as $view => $tab) {
    $hash = md5($card . $tab);
    
    $out[] = [
        [$card, $tab],
        [
            APP::Render('mail/admin/dashboard/' . $view . '/html', 'content', compact('hash')),
            APP::Render('mail/admin/dashboard/' . $view . '/css', 'content', compact('hash')),
            APP::Render('mail/admin/dashboard/' . $view . '/js', 'content', compact('hash'))
        ]
    ];
}

return $out;