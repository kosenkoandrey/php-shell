<?
$title = 'Обзор';
$tab = 'Туннели';
$hash = md5($title . $tab);

return [[
    [$title, $tab],
    [
        APP::Render('tunnels/admin/dashboard/html', 'content', compact('hash')),
        APP::Render('tunnels/admin/dashboard/css', 'content', compact('hash')),
        APP::Render('tunnels/admin/dashboard/js', 'content', compact('hash'))
    ]
]];