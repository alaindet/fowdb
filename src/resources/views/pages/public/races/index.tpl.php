<?php

// VARIABLES
// $races
// $traits
// $sortables
// $sort_races
// $sort_traits

// INPUTS
// sort-races
// sort-traits

?>
<div class="page-header">
	<h1>Races and Traits</h1>
</div>

<div class="row">

  <!-- Sort =============================================================== -->
  <div class="col-xs-12">
    <form
      action="<?=url('races')?>"
      class="form form-inline"
      method="GET"
    >

      <!-- Races -->
      <select class="form-control input-lg" name="sort-races">
        <?php foreach ($sortables as $sortable => $label):
					$checked = ($sortable === $sort_races) ? ' selected="true"' : '';
        ?>
          <option value="<?=$sortable?>"<?=$checked?>><?=$label?></option>
        <?php endforeach; ?>
      </select>

      <!-- Traits -->
      <select class="form-control input-lg" name="sort-traits">
        <?php foreach ($sortables as $sortable => $label):
          $checked = ($sortable === $sort_traits) ? ' selected="true"' : '';
        ?>
          <option value="<?=$sortable?>"<?=$checked?>><?=$label?></option>
        <?php endforeach; ?>
      </select>

			<!-- Submit: mobile -->
			<span class="visible-xs">
				<button type="submit" class="btn btn-lg btn-block fd-btn-default">
					<i class="fa fa-sort"></i>
					Sort
				</button>
			</span>

			<!-- Submit: desktop -->
			<span class="hidden-xs">
				<button type="submit" class="btn btn-lg fd-btn-default">
					<i class="fa fa-sort"></i>
					Sort
				</button>
			</span>

		</form>

		<hr class="fd-hr">

	</div>

	<!-- Races ============================================================== -->
	<div class="col-xs-6">

    <!-- Races title -->
		<h2>
			Races
			<small>(<?=count($races)?> in total)</small>
		</h2>

    <!-- Races list -->
		<ul>
			<?php
				foreach ($races as $race => $amount):
					$link = url('cards', ['race' => urlencode($race)]);
			?>
				<li>
					<a href="<?=$link?>"><?=$race?></a>
					(<?=$amount?>)
				</li>
			<?php endforeach; ?>
		</ul>

	</div><!-- /Races -->
	
	<!-- Traits ============================================================= -->
	<div class="col-xs-6">

    <!-- Traits title -->
		<h2>
			Traits
			<small>(<?=count($traits)?> in total)</small>
		</h2>

    <!-- Traits list -->
		<ul>
			<?php
				foreach ($traits as $trait => $amount):
          $link = url('cards', ['race' => urlencode($trait)]);
			?>
				<li>
					<a href="<?=$link?>"><?=$trait?></a>
					(<?=$amount?>)
				</li>
			<?php endforeach; ?>
		</ul>

	</div><!-- /Traits -->
  
</div>
