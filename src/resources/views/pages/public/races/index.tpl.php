<?php

// VARIABLES
// $races
// $traits

?>
<div class="page-header">
	<h1>Races and Traits</h1>
</div>

<div class="row">

	<!-- Races -->
	<div class="col-xs-6">
		<h2>
			Races
			<small>(<?=count($races)?>)</small>
		</h2>
		<ul>
			<?php
				foreach ($races as $race):
					$encoded = urlencode(strtolower($race));
					$link = url_old('/', ['do' => 'search', 'race' => $encoded]);
			?>
				<li>
					<a href="<?=$link?>" target="_blank">
						<?=$race?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<!-- Traits -->
	<div class="col-xs-6">
		<h2>
			Traits
			<small>(<?=count($traits)?>)</small>
		</h2>
		<ul>
			<?php
				foreach ($traits as $trait):
					$encoded = urlencode(strtolower($trait));
					$link = url_old('/', ['do' => 'search', 'race' => $encoded]);
			?>
				<li>
					<a href="<?=$link?>" target="_blank">
						<?=$trait?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
  
</div>
