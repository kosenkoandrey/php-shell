<?
$title = 'Overview';
$tab = 'Likes';
$hash = md5($title . $tab);

return [[
    [$title, $tab],
    [
        APP::Render('likes/admin/dashboard/html', 'content', compact('hash')),
        APP::Render('likes/admin/dashboard/css', 'content', compact('hash')),
        APP::Render('likes/admin/dashboard/js', 'content', compact('hash'))
    ]
]];