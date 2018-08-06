<?php if (admin_level() > 0): // $adminLevel comes from /admin/index.php ?>
<?php
	// Get previous page if any and delete it from session
	if (isset($_SESSION['previous'])) {
		$_SESSION['previous'];
		$previousPage = $_SESSION['previous'];
		unset($_SESSION['previous']);
	} else {
		$previousPage = "/";
	}

	// Define links
	$links = [
		'master' => [
			'/?p=admin/hash' => 'Hash a string',
			'/?p=admin/helpers' => 'Helpers',
		],
		'judge' => [
			'/?p=admin/cards' => 'Cards',
			'/?p=admin/cr' => 'Comprehensive Rules',
			'/?p=admin/rulings' => 'Rulings',
			// '/?p=admin/requests' => 'Ruling Requests',
			// '/?p=admin/ban' => 'Banlist',
      // '/?p=admin/image' => 'Imaging Tools'
      '/?p=admin/trim-image' => 'Trim image'
		]
	];
?>
	<!-- Header -->
	<div class="page-header"><h1>Admin menu</h1></div>

	<!-- Admin and Judge menu -->
	<div class="row">

		<!-- Previous Page -->
		<div class="col-xs-12 col-sm-12">
			<ul class="list-unstyled">
				<li>
					<a href="<?=$previousPage?>">
						<button type="button" class="btn btn-primary">
							&larr; Previous Page
						</button>
					</a>
				</li>
				<br />
			</ul>
		</div>

		<?php if (admin_level() == 1): /*Admin level 1*/ ?>
			<!-- Admin Menu -->
			<div class="col-xs-12 col-sm-4">
				<ul class="list-unstyled">
					<?php foreach ($links['master'] as $link => $label): ?>
						<!-- <?=$label?> -->
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
				<?php foreach ($links['judge'] as $link => $label): ?>
					<!-- <?=$label?> -->
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
<?php endif; ?>
