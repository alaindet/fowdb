<?php
	// Define links
	$links = [
		'master' => [
			url_old('admin/_artists/select-set') => 'Artists Tool',
			url_old('admin/database') => 'Database (Adminer)',
			url_old('admin/hash') => 'Hashing tool',
			url_old('admin/helpers') => 'Helpers data',
			url_old('admin/lookup') => 'Lookup data',
			url_old('admin/clint') => 'Clint commands',
		],
		'judge' => [
			url_old('admin/cards') => 'Cards',
			url_old('admin/cr') => 'Comprehensive Rules',
			url('rulings/manage') => 'Rulings', // Refactored section!
      		url_old('admin/trim-image') => 'Trim image'
		]
	];
?>
<div class="page-header">
  <h1>Admin menu</h1>
</div>

<!-- Admin and Judge menu -->
<div class="row">

	<?php if (admin_level() === 1): // Super admin ?>
		<div class="col-xs-12 col-sm-4">
			<ul class="list-unstyled">
				<?php foreach ($links['master'] as $link => &$label): ?>
					<li>
						<a href="<?=$link?>">
							<button type="button" class="btn btn-default btn-lg">
								<?=$label?>
							</button>
						</a>
					</li>
					<br />
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<!-- Judge menu -->
	<div class="col-xs-12 col-sm-4">
		<ul class="list-unstyled">
			<?php foreach ($links['judge'] as $link => &$label): ?>
				<li>
					<a href="<?=$link?>">
						<button type="button" class="btn btn-default btn-lg">
							<?=$label?>
						</button>
					</a>
				</li>
				<br />
			<?php endforeach; ?>
		</ul>
	</div>

</div>

<div class="row">
	
</div>
