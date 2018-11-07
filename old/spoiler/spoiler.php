<?php

// ERROR: Missing spoilers at the moment
if (empty(lookup('spoilers.ids'))) {
	alert('There are no spoilers on FoWDB at the moment', 'warning');
	redirect_old('/');
}

?>
<div class="row">
	<?php
		require path_root('old/spoiler/spoiler.sql.php');
		$isSpoiler = true; // Used in options.html.php
		require path_root('old/search/options.html.php');
		require path_root('old/spoiler/spoiler.html.php');
	?>
</div>
