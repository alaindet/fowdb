<?php

// VARIABLES
// (none)

?>
<div class="page-header">
	<h1>Image trimmer</h1>
</div>

<div class="well well-lg">
	<form
		action="<?=fd_url('images/trim')?>"
		method="post"
		enctype="multipart/form-data"
	>
    	<?=fd_csrf_token()?>

		<!-- Browse -->
		<div class="form-group">
			<label for="image-to-trim">Upload card image, max 2Mb</label>
			<input type="file" name="image-to-trim">
		</div>

		<!-- Notes -->
		<p>
			Notes:
			<ul class="fd-list --spaced">
				<li>Works only on solid color borders</li>
				<li>20% tolerance</li>
				<li>All borders are trimmed</li>
				<li>Image is returned in the browser, not stored</li>
			</ul>
		</p>

		<!-- Submit -->
		<div class="form-group">
			<button type="submit" class="btn btn-lg btn-primary">
        		Trim Image
      		</button>
		</div>

	</form>
</div>