<!-- Admin menu link -->
<p>
	<a href="<?=url('admin')?>">
		<button type="button" class="btn btn-default">
			&larr; Admin menu
		</button>
	</a>
	<a href="<?=url_old('admin/cards')?>">
		<button type="button" class="btn btn-default">
			Cards menu
		</button>
	</a>
</p>

<!-- Header -->
<div class="page-header">
	<h1>List of Cards <small>(<?php echo $pagination->tracking(); ?>)</small></h1>
</div>

<!-- Controls -->
<div class="row">
	<div class="col-xs-12">

		<!-- Form -->
		<form class="form form-inline inline" action="" method="post">

			<!-- Label -->
			<label for="sort_rulings">Sort by: </label>
			
			<!-- Sort by -->
			<select class="form-control" name="sort_cards">
				<?php
					// Define sortings
					$sortings = [
						'0' => 'Code (default)',
						'clusters_id' => 'Cluster',
						'attribute' => 'Attribute',
						'total_cost' => 'Total Cost',
						'type' => 'Type',
						'name' => 'Name',
						'atk' => 'ATK',
						'def' => 'DEF',
						'id' => 'ID',
					];

					// Generate an option for each sorting
					foreach ($sortings as $key => $value): 
				?>
					<?php
						// Sticky
						$_POST['sort_cards'] == $key
							? $checked = ' selected=true'
							: $checked = '';
					?>
					<option value="<?=$key?>"<?=$checked?>><?=$value?></option>
				<?php endforeach; ?>
			</select><!-- /Sort by -->
			

			<!-- Sort direction -->
			<?php
				// Sticky
				isset($_POST['sort_desc'])
					? $checked = ' checked=true'
					: $checked = '';
			?>
			<label class="btn btn-default">
				<input type="checkbox" name="sort_desc" value="desc"<?=$checked?> />
				High>Low
			</label><!-- /Sort direction -->


			<!-- Include Valhalla (default is excluded) -->
			<?php
				// Sticky
				isset($_POST['include_valhalla'])
					? $checked = ' checked=true'
					: $checked = '';
			?>
			<label class="btn btn-default">
				<input type="checkbox" name="include_valhalla" value="yes"<?=$checked?> />
				Include Valhalla
			</label><!-- /Include Valhalla -->


			<!-- No mobile -->
			<?php
				// Sticky
				isset($_POST['no_mobile'])
					? $checked = ' checked=true'
					: $checked = '';
			?>
			<label class="btn btn-default visible-xs-inline-block">
				<input type="checkbox" name="no_mobile" value="yes"<?=$checked?> />
				No mobile view
			</label><!-- /No mobile -->


			<!-- Submit -->
			<input class="form-control btn-primary" name="submit-btn" type="submit" value="Go" />
		</form><!-- /Form -->

		<hr>

		<!-- Pagination -->
		<?php echo $pagination->pagelinks(); /*Generate Pagination*/ ?>
		<!-- /Pagination -->

	</div>
</div><!-- /Controls -->

<hr>

<div class="row">
	<!-- CARDS -->
	<div class="col-xs-12">

		<?php
			// Check if user deselected mobile view
			(!isset($_POST['no_mobile']))
				? $mobile_view = " fdb-table-stacked fdb-table-labels fdb-table-manageCards"
				: $mobile_view = "";
		?>

		<table class="table table-striped table-condensed table-responsive<?=$mobile_view?>">
			<thead>
				<tr>
					<th><h4>#</h4></th>
					<th><h4>Action</h4></th>
					<th><h4>Cluster</h4></th>
					<th><h4>Code</h4></th>
					<th><h4>Attrib</h4></th>
					<th><h4>Cost</h4></th>
					<th><h4>Type</h4></th>
					<th><h4>Name</h4></th>
					<th><h4>ATK/DEF</h4></th>
					<th><h4>Text</h4></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
					$counter = 0;
					foreach ($cards as $card):
				?>
					<?php
						// Replace spaces in card code to $nbsp; to avoid line breaks
						$card['code'] = str_replace(" ", "&nbsp;", $card['code']);
						
						// Assemble link to the card page
						$link = url_old('card', [
							'code' => urlencode($card['code'])
						]);
					?>
					<tr>
						<!-- Number # -->
						<td><?php $counter++; echo $counter; ?></td>
						
						<!-- Action -->
						<td>
							<!-- Edit -->
							<form method="post" action="" class="form inline">
								<!-- ID (hidden) -->
								<input type="hidden" name="id" value="<?=$card['id']?>" />
								<!-- Action (hidden) -->
								<input type="hidden" name="form_action" value="edit" />
								<!-- Button -->
								<button type="submit" class="btn btn-info btn-xs">
                  <i class="fa fa-pencil-square-o"></i>
								</button>
							</form><!--
							
							--><!-- Remove --><!--
							--><form method="post" action="" class="form inline form-delete">
								<!-- ID (hidden) -->
								<input type="hidden" name="id" value="<?=$card['id']?>" />
								<!-- Action (action) -->
								<input type="hidden" name="form_action" value="delete" />
								<!-- Button -->
								<button type="submit" class="btn btn-danger btn-xs">
                  <i class="fa fa-trash"></i>
								</button>
							</form>
						</td>

						<!-- Cluster -->
						<td><?php
							switch($card['clusters_id']) {
								case 1: echo 'VAL'; break;
								case 2: echo 'GRM'; break;
								case 3: echo 'ALI'; break;
							}
						?></td>
						
						<!-- Code -->
						<td><?=$card['code']?></td>
						
						<!-- Attribute -->
						<td><?php
							foreach ($card['attribute'] as $attribute) {
								if (!empty($attribute)) {
									echo "<img src='images/icons/{$attribute}.png' />";
								}
							}
						?></td>

						<!-- Cost -->
						<td><?=$card['total_cost']?></td>

						<!-- Type -->
						<td><?=$card['type']?></td>

						<!-- Name -->
						<td>
							<strong>
								<a href="<?=$link?>" target="_blank">
									<?=$card['name']?>
								</a>
							</strong>
						</td>
						
						<!-- ATK/DEF -->
						<td><?php
							echo $card['atkdef'] == '/' ? '' : $card['atkdef'];
						?></td>

						<!-- Text -->
						<td><?=$card['text']?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div><!-- /CARDS -->
</div>

<?php require path_views('components/top-anchor.php'); // Top anchor ?>

<hr>

<?=$pagination->pagelinks();?>

<hr>

<!-- Admin menu link -->
<p>
	<a href="<?=url('admin')?>">
		<button type="button" class="btn btn-default">
			&larr; Admin menu
		</button>
	</a>
	<a href="<?=url_old('admin/cards')?>"
		<button type="button" class="btn btn-default">
			Cards menu
		</button>
	</a>
</p>
