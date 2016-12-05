<?
return [
    'routes' => [
        ['rating\/(?P<item>[a-z0-9\-]+)\/(?P<rating>1|2|3|4|5)\/(?P<id>.*)',    'Rating', 'SetRating'],
        ['rating\/comments\/post\.json',                                        'Rating', 'PostComment']
    ]
];