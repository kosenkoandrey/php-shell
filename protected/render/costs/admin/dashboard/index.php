<?
$title = 'Overview';
$tab = 'Costs';
$hash = md5($title . $tab);

return [[
    [$title, $tab],
    [
        APP::Render('costs/admin/dashboard/html', 'content', compact('hash')),
        APP::Render('costs/admin/dashboard/css', 'content', compact('hash')),
        APP::Render('costs/admin/dashboard/js', 'content', compact('hash'))
    ]
]];