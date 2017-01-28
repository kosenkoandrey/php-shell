<?
$title = 'Обзор';
$tab = 'Счета';
$hash = md5($title . $tab);

return [[
    [$title, $tab],
    [
        APP::Render('billing/admin/dashboard/html', 'content', compact('hash')),
        APP::Render('billing/admin/dashboard/css', 'content', compact('hash')),
        APP::Render('billing/admin/dashboard/js', 'content', compact('hash'))
    ]
]];