<?
$title = 'Разное';
$tab = 'Ошибки';
$hash = md5($title . $tab);

return [[
    [$title, $tab],
    [
        APP::Render('logs/admin/dashboard/html', 'content', compact('hash')),
        APP::Render('logs/admin/dashboard/css', 'content', compact('hash')),
        APP::Render('logs/admin/dashboard/js', 'content', compact('hash'))
    ]
]];