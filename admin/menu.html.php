<?php
	// Define links
	$links = [
		'master' => [
			'temp/admin/artists/select-set' => '(TEMPORARY) Artists Tool',
			'admin/database' => 'Database',
			'admin/hash' => 'Hash a string',
			'admin/helpers' => 'Helpers',
			'admin/clint' => 'Clint commands',
			'admin/lookup' => 'Lookup data',
		],
		'judge' => [
			'admin/cards' => 'Cards',
			'admin/cr' => 'Comprehensive Rules',
			'admin/rulings' => 'Rulings',
      		'admin/trim-image' => 'Trim image'
		]
	];
?>
<!-- Header -->
<div class="page-header"><h1>Admin menu</h1></div>

<!-- Admin and Judge menu -->
<div class="row">

	<?php if (admin_level() == 1): // Super admin ?>
		<div class="col-xs-12 col-sm-4">
			<ul class="list-unstyled">
				<?php foreach ($links['master'] as $link => &$label): ?>
					<li>
						<a href="/?p=<?=$link?>">
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
					<a href="/?p=<?=$link?>">
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
