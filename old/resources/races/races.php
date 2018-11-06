<?php

// RACES ----------------------------------------------------------------------
$_races = database()
	->select(statement('select')
		->select('DISTINCT race')
		->from('cards')
		->where("type IN('Ruler', 'J-Ruler', 'Resonator')")
	)
	->get();
$races = [];
foreach ($_races as &$item) {
	foreach (explode('/', $item['race']) as $race) {
		if (!isset($races[$race])) $races[$race] = 1;
	}
}
$races = array_keys($races);
sort($races);

// TRAITS ---------------------------------------------------------------------
$_traits = database()
	->select(statement('select')
		->select('DISTINCT race')
		->from('cards')
		->where("NOT (type IN('Ruler','J-Ruler','Resonator'))")
	)
	->get();
$traits = [];
foreach ($_traits as &$item) {
	foreach (explode('/', $item['race']) as $trait) {
		if (!isset($traits[$trait])) $traits[$trait] = 1;
	}
}
$traits = array_keys($traits);
sort($traits);

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
