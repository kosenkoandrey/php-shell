<?
$card = 'Overview';
$tab = 'Comments';
$hash = md5($card . $tab);

return [[
    [$card, $tab],
    [
        APP::Render('comments/admin/dashboard/html', 'content', compact('hash')),
        APP::Render('comments/admin/dashboard/css', 'content', compact('hash')),
        APP::Render('comments/admin/dashboard/js', 'content', compact('hash'))
    ]
]];