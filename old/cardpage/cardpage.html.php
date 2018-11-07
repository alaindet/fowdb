<?php

	// Variables
	// $cards

  $counter = 0; // Ruling counter
  foreach ($cards as &$card):
?>
	<div class="row cardpage">

    <!-- Card name -->
		<div class="col-xs-12">
			<div class="page-header" style="margin-top:0">
				<h1 class="text-center sm-text-left">
					<?=$card['name']?>
					<span class="font-100 text-muted text-italic">
						<?=$card['code']?>
					</span>
				</h1>
			</div>
		</div>

		<?php // Admin buttons ----------------------------------------------------
			if (admin_level() > 0):
		?>
			<div class="col-xs-12 text-center sm-text-left">
				
        <a
          href="<?=url("cards/update/{$card['id']}")?>"
          class="btn btn-warning"
        >
          Edit card
				</a>

        <a
          href="<?=url("cards/delete/{$card['id']}")?>"
          class="btn btn-danger"
        >
          Delete card
				</a>
				
        <a
          href="<?=url('rulings/create', ['card-id' => $card['id']])?>"
          class="btn btn-info"
        >
          Add ruling
				</a>

			</div>
		<?php // End admin buttons ------------------------------------------------
			endif;
		?>

		<!-- Card image -->
		<div class="col-xs-12 col-sm-5 box" style="text-align:center;">
			<img
				src="<?=$card['image_path']?>"
				alt="<?=$card['name']?>"
				class="cardpage-img"
			>
		</div>
		
		<!-- Card info -->
		<div class="col-xs-12 col-sm-7 box card-props">
			<?php foreach ($card['display'] as $prop): ?>
        <div class="row card-prop">

          <!-- Property label -->
          <div class="col-xs-3 col-sm-3 prop-label">
            <?=$prop['label']?>
          </div>

          <!-- Property value -->
          <div class="col-xs-9 col-sm-9 prop-value">
            <?=$prop['value']?>
          </div>

        </div>
			<?php endforeach; ?>
		</div>

		<?php // Narp -------------------------------------------------------------
			if ($card['narp']['flag'] > 0):
		?>
			<div class="col-xs-12">
				<?php foreach ($card['narp']['cards'] as $print => &$cards): ?>

					<h3>
						<?=$print?>
						<small>(<?=count($cards)?>)</small>
					</h3>

					<ul class="list-group">
						<?php foreach ($cards as &$code): ?>
					  	<li class="list-group-item">
								<a href="<?=url_old('card', ['code' => urlencode($code)])?>">
									<?=$code?>
								</a>
							</li>
					  <?php endforeach; ?>
					</ul>

				<?php endforeach; ?>
			</div>
		<?php // End Narp ---------------------------------------------------------
			endif;
		?>

		<?php // Rulings ----------------------------------------------------------
			if (!empty($card['rulings'])):
		?>
			<div class="col-xs-12 f-rulings">

				<h2 class="text-center sm-text-left">
					Rulings
					<small>(<?=count($card['rulings'])?>)</small>
				</h2>

				<div class="list-group f-rulings-list">
					<?php foreach ($card['rulings'] as $item): $counter++; ?>
					  <div class="list-group-item f-rulings-item">
					    <h4 class="list-group-item-heading">
					    	<?php if ($item['is_errata']): ?>
					    		<div class="f-rulings-errata">Errata</div>
					    	<?php endif; ?>
					    	<div class="f-rulings-date"><?=$item['date']?></div>
					    	<div class="f-rulings-buttons">
					    		
									<a
										href="#ruling<?=$counter?>"
										name="ruling<?=$counter?>"
										class="btn btn-sm btn-link"
									>
										Share
									</a>

					    		<?php if (admin_level() > 0): ?>
						    		
										<!-- Edit -->
										<a
											href="<?=url("rulings/update/{$item['id']}")?>"
											class="btn btn-sm btn-warning"
                    >
                      Edit
                    </a>

										<!-- Delete -->
						    		<a
											href="<?=url("rulings/delete/{$item['id']}")?>"
											class="btn btn-sm btn-danger"
                    >
                      Delete
                    </a>

					    		<?php endif; ?>

					    	</div>
					    </h4>
					    <p class="list-group-item-text"><?=$item['text']?></p>
					   </div>

					<?php endforeach; ?>
				</div>
			</div>
		<?php // End rulings ------------------------------------------------------
			endif;
		?>

	</div>
<?php endforeach; ?>