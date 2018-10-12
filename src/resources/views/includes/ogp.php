<?php

global $results, // Where there search results?
    $ogp_image; // From ogpinfo.sql.php

// Declare ogp array and $metas meta tags string
$ogp = [];

// Initialize meta tags with Facebook App ID
$metas = "<meta property=\"fb:app_id\" content=\"1623441987919274\">";

// TITLE
$ogp['title'] = $page_title." || ".APP_NAME;

// TYPE
$ogp['type'] = 'article';

// IMAGE
if (isset($ogp_image)) {
    $ogp['image'] = [];
    foreach ($ogp_image as &$imagepath) {
        $ogp['image'][] = APP_DOMAIN.$imagepath;
    }
    $ogp['image'][] = APP_DOMAIN."images/logosquare.jpg"; // Default
}
else {
    $ogp['image'] = APP_DOMAIN."images/logosquare.jpg";
    $ogp['image:width'] = "300";
    $ogp['image:height'] = "300";
}

// URL
$ogp['url'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; // URL

// SITE NAME
$ogp['site_name'] = APP_NAME;

// DESCRIPTION
$ogp['description'] = "Browse all FoW TCG cards with FoWDB! Responsive design, advanced search filters, online tools, updated SPOILER section and more.";


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
