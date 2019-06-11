<?php
/*
 * INPUT
 * ?any data
 * ?string title
 */

if (isset($data)) {
    echo fd_log_html($data, $title ?? 'log');
} else {
    echo "No data";
}
