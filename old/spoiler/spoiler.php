<?php if (empty(cached('spoiler.sets'))): ?>
	<div class="panel panel-default">
		<div class="panel-heading"><h4>Coming soon..</h4></div>
		<div class="panel-body">
			<p style="font-size:2em;">Get back here soon!</p>
		</div>
	</div>
<?php else: ?>
	<div class="row">
		<?php
      require path_root('old/spoiler/spoiler.sql.php');
			$isSpoiler = true; // Used in options.html.php
			require path_root('old/search/options.html.php');
			require path_root('old/spoiler/spoiler.html.php');
		?>
	</div>
<?php endif; ?>
