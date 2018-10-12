<?php if (empty(\App\Helpers::get('spoiler'))): /*NO SPOILERS*/ ?>
	<div class="panel panel-default">
		<div class="panel-heading"><h4>Coming soon..</h4></div>
		<div class="panel-body">
			<p style="font-size:2em;">Get back here soon!</p>
		</div>
	</div>
<?php else: ?>
	<div class="row">
		<?php
			// Load spoiler cards
			require DIR_ROOT . '/spoiler/spoiler.sql.php';
			
			// Set flag (used in options.html.php)
			$is_spoiler = true;
			
			// Load options side panel
			require DIR_ROOT . '/search/options.html.php';
			
			// Load spoiler container
			require DIR_ROOT . '/spoiler/spoiler.html.php';
		?>
	</div>
<?php endif; ?>
