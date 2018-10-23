<?php

global $results, // Where there search results?
    $ogp_image; // From ogpinfo.sql.php

$ogp = [];

$app = [
    'name' => config('app.name'),
    'url' => config('app.url'),
    'description' => config('app.description')
];

// Initialize meta tags with Facebook App ID
$metas = "<meta property=\"fb:app_id\" content=\"1623441987919274\">";

// TITLE
$ogp['title'] = "{$page_title} || {$app['name']}";

// TYPE
$ogp['type'] = 'article';

// IMAGE
if (isset($ogp_image)) {
    $ogp['image'] = [];
    foreach ($ogp_image as &$imagepath) {
        $ogp['image'][] = $app['url'].$imagepath;
    }

    // Default image (logo)
    $ogp['image'][] = $app['url'].'/images/logosquare.jpg';
}
else {
    $ogp['image'] = $app['url'].'/images/logosquare.jpg';
    $ogp['image:width'] = "300";
    $ogp['image:height'] = "300";
}

// URL
$ogp['url'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; // URL

// SITE NAME
$ogp['site_name'] = $app['name'];

// DESCRIPTION
$ogp['description'] = $app['description'];


// Generate meta tags
foreach($ogp as $property => &$content) {
    if (is_array($content)) {
        foreach($content as &$subcontent) {
            $metas .= '<meta property="og:'.$property.'" content="'.$subcontent.'">';
        }
    }
    else {
        $metas .= '<meta property="og:'.$property.'" content="'.$content.'">';
    }
}

echo $metas;
