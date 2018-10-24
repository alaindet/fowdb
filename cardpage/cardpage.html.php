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
				<h2><?=$card['name']?></h2>
			</div>
		</div>

		<?php // Admin buttons ----------------------------------------------------
			if (admin_level() > 0):
				$id =& $card['id'];
        $do = 'form_action';
		?>
			<div class="col-xs-12">
				
				<a href="<?=url_old('admin/cards', [$do => 'edit', 'id' => $id])?>">
					<button type="button" class="btn btn-warning btn-sm cardpage-btn">
						<span class="glyphicon glyphicon-edit"></span>
						Edit card
					</button>
				</a>

				<a href="<?=url_old('admin/cards', [$do => 'delete', 'id' => $id])?>">
					<button type="button" class="btn btn-danger btn-sm cardpage-btn">
						<span class="glyphicon glyphicon-remove"></span>
						Delete card
					</button>
				</a>
				
				<a href="<?=url_old('admin/rulings', [$do => 'create', 'card_id' => $id])?>">
					<button type="button" class="btn btn-default btn-sm cardpage-btn">
						<span class="glyphicon glyphicon-plus"></span>
						Add ruling
					</button>
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
			/>
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
								<a href="<?='/?p=card&code='.str_replace(' ','+',$code)?>">
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

				<h3>
					Rulings
					<small>(<?=count($card['rulings'])?>)</small>
				</h3>

				<div class="list-group f-rulings-list">
					<?php foreach ($card['rulings'] as $item): $counter++; ?>
					  <div class="list-group-item f-rulings-item">
					    <h4 class="list-group-item-heading">
					    	<?php if ($item['is_errata']): ?>
					    		<div class="f-rulings-errata">Errata</div>
					    	<?php endif; ?>
					    	<div class="f-rulings-date"><?=$item['created']?></div>
					    	<div class="f-rulings-buttons">
					    		
									<a
										href="#ruling<?=$counter?>"
										name="ruling<?=$counter?>"
										class="btn btn-sm btn-link"
									>Share</a>

					    		<?php if (admin_level() > 0): ?>
						    		
										<!-- Edit -->
										<a
											href="<?=url_old('admin/rulings', [
												'form_action' => 'edit',
												'id' => $item['id'],
												'code' => str_replace(' ', '+', $card['code'])
											])?>"
											class="btn btn-sm btn-warning"
                    >
                      Edit
                    </a>

										<!-- Delete -->
						    		<a
											href="<?=url_old('admin/rulings', [
												'form_action' => 'delete',
												'id' => $item['id'],
												'code' => str_replace(' ', '+', $card['code'])
											])?>"
											class="btn btn-sm btn-danger"
                    >
                      Delete
                    </a>

					    		<?php endif; ?>

					    	</div>
					    </h4>
					    <p class="list-group-item-text"><?=$item['ruling']?></p>
					   </div>

					<?php endforeach; ?>
				</div>
			</div>
		<?php // End rulings ------------------------------------------------------
			endif;
		?>

	</div>
<?php endforeach; ?>
