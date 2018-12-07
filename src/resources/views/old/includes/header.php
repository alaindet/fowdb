<?php
$links = [
	'Search' =>  [
		'url' => url_old('search'),
		'icon' => 'fa fa-search'
	],
	'Spoiler' => [
		'url' => url_old('spoiler'),
		'icon' => 'fa fa-th-large'
	],
	'Resources' => [
		'url' => '',
		'icon' => 'fa fa-file-text',
		'dropdown' => [
			[
				'label' => 'Comprehensive Rules',
				'url' => url('cr')
			],
			[
				'label' => 'Banlists',
				'url' => url_old('resources/ban')
			],
			[
				'label' => 'Errata',
				'url' => url_old('resources/errata')
			],
			[
				'label' => 'Formats',
				'url' => url('formats')
			],
			[
				'label' => 'Races and Traits',
				'url' => url('races')
			],
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
							<a href="<?=url('profile')?>">
								<i class="fa fa-user"></i>
								Profile
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

	<div class="fd-alerts">
    <?php foreach (\App\Services\Alert::get() as $alert): ?>
      <div class="fd-alert alert alert-<?=$alert['type']?> alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">
			&times;
		</a>
        <div class="fd-alert-content"><?=$alert['message']?></div>
      </div>  
    <?php endforeach; ?>
  </div>

	<!-- Logo -->
	<div id="logo">
		<a href="<?=url('/')?>">
			<img src="<?=asset('images/logo.png')?>">
		</a>
	</div>
</header>
