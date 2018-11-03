<!-- Header -->
<div class="page-header">
	<h1>Manage Cards</h1>
</div><!-- /Header -->

<!-- Buttons -->
<div class="row">
	<div class="col-xs-12">
		<form action="" method="POST">

			<!-- Create -->
			<p>
				<button type="submit" name="form_action" value="create" class="btn btn-success">
					<i class="fa fa-plus"></i>
					Create new card
				</button>
			</p>

			<!-- Edit -->
			<p>
				<button type="submit" name="menu_action" value="list" class="btn btn-primary">
					<i class="fa fa-pencil-square-o"></i>
					Edit or Delete a card
				</button>
			</p>
		</form>
	</div>
</div><!-- /Buttons -->

<hr>

<!-- Admin menu link -->
<p>
	<a href="<?=url('admin')?>">
		<button type="button" class="btn btn-default">
			&larr; Admin menu
		</button>
	</a>
</p>
