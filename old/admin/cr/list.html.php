<h2 class="hider pointer" data-target="#cr-list">
	<small>
		<i class="fa fa-bars"></i>
	</small>
	Edit/Delete
	<small>(Show/hide)</small>
</h2>
<hr>
<?php if (empty($crs)): ?>
	<pre>No CRs to show yet!</pre>
<?php else: ?>
    <ul id="cr-list" class="fd-list">
    	<?php foreach ($crs as $cr): ?>
    		<li>
				<form
					action="<?=url_old('admin/cr/action')?>"
					method="post"
					class="form-inline inline"
				>
					<!-- Token -->
					<?=csrf_token()?>
					<!-- ID -->
					<input type="hidden" name="id" value="<?=$cr['id']?>">
					<!--  Delete -->
					<button type="submit" class="btn btn-xs btn-danger" name="admin-cr-action" value="delete-form">
						<i class="fa fa-trash"></i>
						Delete
					</button>
					<!-- Edit -->
					<button type="submit" class="btn btn-xs btn-primary" name="admin-cr-action" value="update-form">
						<i class="fa fa-pencil-square-o"></i>
						Update
					</button>
				</form>

				<!-- View -->
				<a
					href="<?=url_old(
						'resources/cr',
						[ 'v' => $cr['version'] ]
					)?>"
					target="_blank"
					class="btn btn-link btn-xs"
				>
					<i class="fa fa-external-link"></i>
					View
				</a>
					
				<br><strong>DEFAULT:</strong>
				<?php echo $cr['is_default'] ? "<span class='text-danger'><strong>YES</strong></span>" : "NO"; 5?>,
				<br><strong>VERSION:</strong> CR <?=$cr['version']?>,
				<br><strong>CREATION:</strong> <?=$cr['date_inserted']?>,
				<br><strong>LEGALITY:</strong> <?=$cr['date_validity']?>
    		</li>
    		<br>
    	<?php endforeach; ?>
    </ul>
<?php endif; ?>
