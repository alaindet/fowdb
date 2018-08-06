<?php
/*
 * VARIABLES
 * $sets
 */
?>

<!-- Image Trimmer -->
<div class="page-header">
	<h2>Trim single image</h2>
</div>
<div class="well well-lg">
	<form action="/test-laravel/public/image/trim" method="post" enctype="multipart/form-data">

		<!-- Browse -->
		<div class="form-group">
			<label for="image">Upload card image, max 2Mb</label>
			<input type="file" name="image">
		</div>

		<!-- Notes -->
		<p>
			<ul class="fdb-list">Notes:
				<li>Works only on solid color borders</li>
				<li>20% tolerance</li>
				<li>All borders are trimmed</li>
				<li>Image is returned in the browser, not stored</li>
			</ul>
		</p>

		<!-- Submit -->
		<div class="form-group">
			<button type="submit" class="btn btn-lg btn-primary">Trim Image</button>
		</div>
	</form>
</div>


<!-- Multiple images manipulation -->
<div class="page-header">
	<h1>Multiple images manipulation</h1>
</div>
<div class="well well-lg">
	<form action="" method="post" enctype="multipart/form-data">

		<!-- Uploader -->
		<div class="form-group">
			<label for="sent-file">Upload card image(s), max 2Mb total, can upload multiple</label>
			<input type="file" name="files[]" multiple="" />
		</div>

		<!-- Delete all -->
		<div class="form-group">
			<input type="submit" name="submit" value="Empty uploads folder" class="btn btn-danger" />
		</div>

		<!-- Watermark -->
		<p>
			<label class="btn btn-default">
				<input type="checkbox" name="watermark" value="yes" checked="true" />
				Watermark?
			</label>
		</p>

		<!-- Set -->
		<div class="form-group">
			<select id="setcode" class="form-control" name="cluster-set">
				<option name="set_default" value=0>Choose a set..</option>
				<?php foreach ($clusters as $cid => &$cluster): ?>
					<optgroup label="<?=$cluster['name']?>">
					<?php foreach ($cluster['sets'] as $scode => &$sname): ?>
						<option value="<?=$cid.".".$scode?>">
	          	<?=$sname?> - (<?=$cid."/".$scode?>)
	          </option>
					<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>	
			</select>
		</div>

		<!-- Submit -->
		<div class="form-group">
			<input type="submit" name="submit" value="Send image(s)" class="btn btn-primary btn-lg" />
		</div>
	</form>
</div>

<!-- Admin menu link -->
<a href="/index.php?p=admin">
	<button type="button" class="btn btn-default">
			&larr; Admin menu
	</button>
</a>
