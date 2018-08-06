<!-- Admin menu link -->
<p>
	<a href="/index.php?p=admin">
		<button type="button" class="btn btn-default">
				&larr; Admin menu
		</button>
	</a>
</p>

<!-- Header -->
<div class="page-header">
	<h1>List of Rulings <small>(<?php echo $pagination->tracking(); ?>)</small></h1>
</div>

<div class="row">
	<div class="col-xs-12">
	
		<!-- Create new ruling -->
		<p>
			<form method="post" action="">
				<!-- Action -->
				<input type="hidden" name="form_action" value="create" />
				<!-- Button -->
				<button type="submit" class="btn btn-success btn-lg">
					<span class="glyphicon glyphicon-plus"></span>
					Create new ruling
				</button>
			</form><!-- /Create new ruling -->
		</p>
	
		<!-- Sort -->
		<form class="form form-inline inline" action="" method="post">
			<!-- Label -->
			<label for="sort_rulings">Sort by: </label>
			
			<!-- Options -->
			<select class="form-control" name="sort_rulings">
				<?php
					$sorts = [
						'0' => 'Code (default)'
						,'name' => 'Name'
						,'date' => 'Date'
						,'edited' => 'Edited'
						,'errata' => 'Errata'
					];
					foreach ($sorts as $key => $value): 
				?>
					<?php
							// Sticky
							$_POST['sort_rulings'] == $key
								? $checked = ' selected=true'
								: $checked = '';
					?>
					<option value="<?=$key?>"<?=$checked?>><?=$value?></option>
				<?php endforeach; ?>
			</select><!-- /Options -->
			

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
		</form><!-- /Sort -->

		<hr>

		<!-- Pagination -->
		<?php echo $pagination->pagelinks(); /*Generate Pagination*/ ?>
		<!-- /Pagination -->

	</div>
</div><!-- /Header -->

<hr>

<div class="row">
	<!-- RULINGS -->
	<div class="col-xs-12">

		<?php
			// Check if user deselected mobile view
			(!isset($_POST['no_mobile']))
				? $mobile_view = " fdb-table-stacked fdb-table-labels fdb-table-manageRulings"
				: $mobile_view = "";
		?>

		<table class="table table-striped table-condensed table-responsive<?=$mobile_view?>">
			<thead>
				<tr>
					<th><h4>#</h4></th>
					<th><h4>Controls</h4></th>
					<th><h4>Code</h4></th>
					<th><h4>Name</h4></th>
					<th><h4>Date</h4></th>
					<th><h4>Edited</h4></th>
					<th><h4>Errata</h4></th>
					<th><h4>Ruling</h4></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
					$counter = 0;
					foreach ($rulings as $ruling):
				?>
					<?php
						// Replace spaces in card code to $nbsp; to avoid line breaks
						$ruling['code'] = str_replace(" ", "&nbsp;", $ruling['code']);
						
						// Assemble link to the card page
						$link = '?p=card&code=' . str_replace("&nbsp;", "+", $ruling['code']);
					?>
					<tr>
						<!-- Number # -->
						<td><?php $counter++; echo $counter; ?></td>
						
						<!-- Controls -->
						<td>
							<!-- Edit -->
							<form method="post" action="" class="form inline">
								<!-- ID (hidden) -->
								<input type="hidden" name="id" value="<?=$ruling['id']?>" />
								<!-- Card code (hidden) -->
								<input type="hidden" name="code" value="<?=$ruling['code']?>" />
								<!-- Action -->
								<input type="hidden" name="form_action" value="edit" />
								<!-- Button -->
								<button type="submit" class="btn btn-info btn-xs">
									<span class="glyphicon glyphicon-edit"></span>
								</button>
							</form>
							
							<!-- Remove -->
							<form method="post" action="" class="form inline form-delete">
								<!-- ID (hidden) -->
								<input type="hidden" name="id" value="<?=$ruling['id']?>" />
								<!-- Card code (hidden) -->
								<input type="hidden" name="code" value="<?=$ruling['code']?>" />
								<!-- Action -->
								<input type="hidden" name="form_action" value="delete" />
								<!-- Button -->
								<button type="submit" class="btn btn-danger btn-xs">
									<span class="glyphicon glyphicon-remove"></span>
								</button>
							</form>
						</td>
						
						<!-- Code -->
						<td><?=$ruling['code']?></td>
						
						<!-- Name -->
						<td>
							<a href="<?=$link?>" target="_blank"><?=$ruling['name']?></a>
						</td>
						
						<!-- Date -->
						<td><?=$ruling['created']?></td>
						
						<!-- Edited -->
						<td><?=$ruling['is_edited']?></td>
						
						<!-- Errata -->
						<td><?=$ruling['is_errata']?></td>
						
						<!-- Ruling -->
						<td><?=$ruling['ruling']?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div><!-- /RULINGS -->
</div>

<!-- To top -->
<a href="#top" class="toTop">Top</a>

<hr>

<!-- Pagination -->
<?php echo $pagination->pagelinks(); /*Generate Pagination*/ ?>
<!-- /Pagination -->

<hr>

<!-- Admin menu link -->
<p>
	<a href="/index.php?p=admin">
		<button type="button" class="btn btn-default">
				&larr; Admin menu
		</button>
	</a>
</p>