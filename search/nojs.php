<?php

$url = $_SERVER['HTTP_REFERER'];

$matches = [];

preg_match("~page=([0-9]+)~", $url, $matches);

if (!empty($matches)) {

    $pageString =& $matches[0];
    $page = (int) $matches[1];
    $url = str_replace($pageString, "page=".(++$page), $url);
}
else {
    $url .= "&page=2";
}

header("Location: ".$url);
