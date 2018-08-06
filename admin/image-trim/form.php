<div class="page-header">
	<h2>Trim single image</h2>
</div>

<div class="well well-lg">
	<form
    action="/?p=admin/trim-image"
    method="post"
    enctype="multipart/form-data"
  >
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
			<button type="submit" class="btn btn-lg btn-primary">
        Trim Image
      </button>
		</div>
	</form>
</div>
