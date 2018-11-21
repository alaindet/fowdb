<h2 class="hider pointer" data-target="#cr-new">
	<small>
		<i class="fa fa-bars"></i>
	</small>
	New
	<small>(Show/hide)</small>
</h2>
<hr>
<form
	id="cr-new"
	action="<?=url_old('admin/cr/action')?>"
	method="post"
	enctype="multipart/form-data"
	class="form-horizontal">

	<!-- Action and Token -->
	<input type="hidden" name="admin-cr-action" value="create">
	<?=csrf_token()?>

	<!-- Uploader -->
	<div class="form-group form-section">
		<label class="col-sm-2">
			TXT File
		</label>
		<div class="col-sm-10">
			<input type="file" name="crfile">
		</div>
	</div>
	
	<!-- Version -->
	<div class="form-group form-section">
		<label class="col-sm-2">Version</label>
		<div class="col-sm-10">
			<input type="text" name="version" placeholder="Ex.: 6.3a" class="form-control">
		</div>
	</div>
	
	<!-- Validity -->
	<div class="form-group form-section">
		<label class="col-sm-2">
			Legal from<br>
			(<strong>yyyy-mm-dd</strong>)
		</label>
		<div class="col-sm-10">
			<input type="text" name="validity" placeholder="Ex.: 2017-03-19" class="form-control">
		</div>
	</div>
	
	<!-- Set as default -->
	<div class="form-group form-section">
		<label class="col-sm-2">Default</label>
    	<div class="col-sm-10">
            <label class="btn btn-default active">
                <input name="set-default" checked="true" type="checkbox">
                Set as new default
            </label>
		</div>
	</div>
	
	<!-- Submit -->
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<button type="submit" class="btn btn-success btn-lg">
				Create
			</button>
		</div>
	</div>
</form>
<hr>
