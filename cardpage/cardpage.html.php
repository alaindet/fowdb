<?php
	$cardFeatures = \App\Helpers::get('cardfeatures');
	$counter = 0; // Ruling counter
	foreach ($cards as &$card):
?>
	<div class="row cardpage">

		<div class="col-xs-12">
			<div class="page-header" style="margin-top:0">
				<h2><?=$card['cardname']?></h2>
			</div>
		</div>

		<?php if (admin_level() > 0): // Admin buttons ?>
			<div class="col-xs-12">
				<!-- Edit -->
				<a href="/index.php?p=admin/cards&form_action=edit&id=<?=$card['id']?>">
					<button type="button" class="btn btn-warning btn-sm cardpage-btn">
						<span class="glyphicon glyphicon-edit"></span>
						Edit card
					</button>
				</a>
				<!-- Delete -->
				<a href="/index.php?p=admin/cards&form_action=delete&id=<?=$card['id']?>">
					<button type="button" class="btn btn-danger btn-sm cardpage-btn">
						<span class="glyphicon glyphicon-remove"></span>
						Delete card
					</button>
				</a>
				<!-- Add -->
				<a href="/index.php?p=admin/rulings&form_action=create&card_id=<?=$card['id']?>">
					<button type="button" class="btn btn-default btn-sm cardpage-btn">
						<span class="glyphicon glyphicon-plus"></span>
						Add ruling
					</button>
				</a>
			</div>
		<?php endif; ?>

		<!-- IMAGE -->
		<div class="col-xs-12 col-sm-5 box" style="text-align:center;">
			<img src="<?=$card['image_path']?>" alt="<?=$card['cardname']?>" class="cardpage-img">
		</div>
		
		<!-- CARD INFO -->
		<div class="col-xs-12 col-sm-7 box card-props">
			<?php
				$excludeProps = ['id', 'backside', 'narp', 'image_path', 'thumb_path', 'rulings'];
			?>
			<?php foreach ($card as $prop => &$value): ?>
				<?php if (!in_array($prop, $excludeProps) && (!empty($value) || $value == 0) ): ?>
					<div class="row card-prop">
						<div class="col-xs-3 col-sm-3 prop-label"><?=$cardFeatures[$prop]?></div>
						<div class="col-xs-9 col-sm-9 prop-value"><?=$value?></div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<?php require __DIR__."/cr-update.html.php"; // CR Update ?>

		<?php if ($card['narp']['flag'] > 0): // NARPs ?>
			<div class="col-xs-12">
				<?php foreach ($card['narp']['cards'] as $label => &$list): ?>
					<h3><?=$label?>&nbsp;<small>(<?=count($list)?>)</small></h3>
					<ul class="list-group">
						<?php foreach ($list as &$code):
							$link = "/?p=card&code=".str_replace(" ", "+", $code);
						?>
					  	<li class="list-group-item"><a href="<?=$link?>"><?=$code?></a></li>
					  <?php endforeach; ?>
					</ul>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if (!empty($card['rulings'])): // Rulings ?>
			<!-- Rulings -->
			<div class="col-xs-12 f-rulings">
				<h3>Rulings&nbsp;<small>(<?=count($card['rulings'])?>)</small></h3>
				<div class="list-group f-rulings-list">
					<?php foreach ($card['rulings'] as $item): $counter++; ?>
					  <div class="list-group-item f-rulings-item">
					    <h4 class="list-group-item-heading">
					    	<?php if ($item['is_errata']): ?>
					    		<div class="f-rulings-errata">Errata</div>
					    	<?php endif; ?>
					    	<div class="f-rulings-date"><?=$item['created']?></div>
					    	<div class="f-rulings-buttons">
					    		<a href="#ruling<?=$counter?>" name="ruling<?=$counter?>" class="btn btn-sm btn-link">Share</a>
					    		<?php if (admin_level() > 0):
					    			$code       = str_replace(" ", "+", $card['cardcode']);
					    			$linkEdit   = "/?p=admin/rulings&form_action=edit&id={$item['id']}&code={$code}";
					    			$linkDelete = "/?p=admin/rulings&form_action=delete&id={$item['id']}&code={$code}";
					    		?>
						    		<a href="<?=$linkEdit?>" class="btn btn-sm btn-warning">Edit</a>
						    		<a href="<?=$linkDelete?>" class="btn btn-sm btn-danger">Delete</a>
					    		<?php endif; ?>
					    	</div>
					    </h4>
					    <p class="list-group-item-text"><?=$item['ruling']?></p>
					   </div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php endforeach; ?>
