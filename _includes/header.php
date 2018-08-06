<?php
$links = [
	'Search' =>  ['url' => '?p=search',  'icon' => 'fa fa-search'],
	'Spoiler' => ['url' => '?p=spoiler', 'icon' => 'fa fa-th-large'],
	'Resources' => [
		'url' => '',
		'icon' => 'fa fa-file-text',
		'dropdown' => [
			['label' => 'Comprehensive Rules', 'url' => '?p=resources/cr'],
			['label' => 'Banlists',            'url' => '?p=resources/ban'],
			['label' => 'Errata',              'url' => '?p=errata'],
			['label' => 'Formats',             'url' => '?p=resources/formats'],
			['label' => 'Races and Traits',    'url' => '?p=resources/races'],
			['label' => 'Rulers',              'url' => '?p=resources/rulers']
		]
	],
];
?>
<header>
	<!-- Navigation -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">

			<!-- Navbar header -->
			<div class="navbar-header">

				<!-- Hamburger -->
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#fowdb-navbar">
					<i class="fa fa-lg fa-bars"></i>
				</button>

				<!-- Brand -->
				<ul>
					<!-- Home -->
					<li>
						<a href="http://fowdb.altervista.org" class="navbar-brand"><i class="fa fa-home fa-lg"></i></a>
					</li>
					<!-- Facebook -->
					<li class="visible-xs-inline">
						<a href="https://www.facebook.com/fowdb" class="navbar-brand"lass="navbar-brand">
							<i class="fa fa-lg fa-facebook-square"></i>
						</a>
					</li>
				</ul>
			</div>

			<!-- Navbar content -->
			<div class="collapse navbar-collapse" id="fowdb-navbar">
				<ul class="nav navbar-nav">
					<?php
						$page = '';
						if (isset($_GET['p']) OR isset($_GET['do'])) {
							$page = isset($_GET['p']) ? ucfirst($_GET['p']) : ucfirst($_GET['do']);
						}
					?>
					<?php if (admin_level() > 0): ?>
						<li>
							<a href="/?p=admin" class="menu-admin">
								<i class="fa fa-unlock"></i>
								Admin
							</a>
						</li>
					<?php endif; ?>
					<?php foreach ($links as $label => &$props): ?>
						<?php if (isset($props['dropdown'])): // Dropdown ?>
							<li class="dropdown">
								<a href="#" class="dropdown toggle" data-toggle="dropdown">
									<i class="<?=$props['icon']?>"></i>
									<?=$label?>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<?php foreach ($props['dropdown'] as &$item): ?>
										<li><a href="<?=$item['url']?>"><?=$item['label']?></a></li>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php else: // Normal link ?>
							<?php
								$active = ($label == $page) ? ' class="active"' : '';
								$class = isset($props['class']) ? ' class="'.$props['class'].'"' : '';
							?>
							<li<?=$active?>>
								<a href="<?=$props['url']?>"<?=$class?>>
									<i class="<?=$props['icon']?>"></i>
									<?=$label?>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<!-- Right navbar -->
				<ul class="nav navbar-nav navbar-right hidden-xs">
					<li>
						<a href="https://www.facebook.com/fowdb">
							<i class="fa fa-lg fa-facebook-square"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<?php if (isset($_SESSION['notif'])): // Notification
		$notification = $_SESSION['notif'];
		if (isset($_SESSION['notify-type'])) {
			$type = "alert-".$_SESSION['notify-type'];
			unset($_SESSION['notif-type']);
		}
		$type = "alert-info";
		unset($_SESSION['notif']);
	?>
		<div class="notification">
			<div class="col-xs-12">
				<div class="alert <?=$type?>">
					<span class="notif-remove pointer">&times;</span>
					<span class="notif-content"><?=$notification?></span>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<!-- Logo -->
	<div id="logo">
		<a href="index.php"><img src="/_images/logo.png"></a>
	</div>
</header>
