<?
$title = 'Обзор';
$tab = 'Новые пользователи';
$hash = md5($title . $tab);

return [[
    [$title, $tab],
    [
        APP::Render('users/admin/dashboard/html', 'content', compact('hash')),
        APP::Render('users/admin/dashboard/css', 'content', compact('hash')),
        APP::Render('users/admin/dashboard/js', 'content', compact('hash'))
    ]
]];