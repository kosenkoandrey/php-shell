<?
$title = 'Обзор';
$tab = 'Посещаемость';
$hash = md5($title . $tab);

return [[
    [$title, $tab],
    [
        APP::Render('analytics/admin/dashboard/html', 'content', compact('hash')),
        APP::Render('analytics/admin/dashboard/css', 'content', compact('hash')),
        APP::Render('analytics/admin/dashboard/js', 'content', compact('hash'))
    ]
]];