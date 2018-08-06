<h2 class="hider pointer" data-target="#cr-list">
	<small><span class="glyphicon glyphicon-menu-hamburger"></span></small>
	Edit/Delete
	<small>(Show/hide)</small>
</h2>
<hr>
<?php if (empty($crs)): ?>
	<pre>No CRs to show yet!</pre>
<?php else: ?>
    <ul id="cr-list" class="fdb-list">
    	<?php foreach ($crs as $cr): ?>
    		<li>
    			<div class="fdb-li--border">
    				<form
    					action="index.php?p=admin/cr/action"
    					method="post"
    					class="form-inline inline">
    					<!-- Token -->
    					<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
    					<!-- ID -->
    					<input type="hidden" name="id" value="<?=$cr['id']?>">
    					<!--  Delete -->
    					<button type="submit" class="btn btn-xs btn-danger" name="admin-cr-action" value="delete-form">
    						<span class="glyphicon glyphicon-remove"></span>
    						Delete
    					</button>
    					<!-- Edit -->
    					<button type="submit" class="btn btn-xs btn-primary" name="admin-cr-action" value="update-form">
    						<span class="glyphicon glyphicon-edit"></span>
    						Update
    					</button>
    				</form>
    				<!-- View -->
    				<a href="/?p=resources/cr&v=<?=$cr['version']?>" target="_blank" class="btn btn-xs btn-link">
    					<span class="glyphicon glyphicon-new-window"></span>
    					View
    				</a>
    				<br><strong>DEFAULT:</strong>
    				<?php echo $cr['is_default'] ? "<span class='text-danger'><strong>YES</strong></span>" : "NO"; 5?>,
    				<br><strong>VERSION:</strong> CR <?=$cr['version']?>,
    				<br><strong>CREATION:</strong> <?=$cr['date_inserted']?>,
    				<br><strong>LEGALITY:</strong> <?=$cr['date_validity']?>
    			</div>
    		</li>
    		<br>
    	<?php endforeach; ?>
    </ul>
<?php endif; ?>