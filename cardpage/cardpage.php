<?php

// Get data -------------------------------------------------------------------
$cards = \App\Legacy\Card::getCardPageData();

// Build Open Graph Protocol data ---------------------------------------------
$url = config('app.url');
$title = "{$cards[0]['name']} ({$cards[0]['code']}) ~ ".config('app.name');
$imageUrl = $url . '/' . $cards[0]['thumb_path'];
$cardUrl = $url . url_old('card', [ 'code' => urlencode($cards[0]['code']) ]);
$ogp = [
    'title' => $title . '  ',
    'url' => $cardUrl,
    'image' => [
        'url' => $imageUrl,
        'alt' => $title
    ]
];

// Print card page ------------------------------------------------------------
echo view(
    $title,
    'cardpage/cardpage.html.php',
    [ 'ogp' => $ogp ],
    [ 'cards' => $cards ]
);
