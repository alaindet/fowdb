<?php

echo "<div class=\"row\">";

// Load search form
require __DIR__ . "/search.html.php";

// Check for search results
if ($thereWereResults) {

  // Load options panel
  require __DIR__ . "/options.html.php";

  // Load results panel
  require __DIR__ . "/results.html.php";
}

echo "</div>";
