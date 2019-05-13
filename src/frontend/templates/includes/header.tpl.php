<?php

$links = [
	"Search" =>  [
		"url" => "/cards/search",
		"icon" => "fa fa-search"
	],
	"Spoiler" => [
		"url" => "/spoiler",
		"icon" => "fa fa-th-large"
	],
	"Game" => [
		"icon" => "fa fa-file-text",
		"dropdown" => [
			[
				"label" => "Comprehensive Rules",
				"url" => "/cr"
			],
			[
				"label" => "Banlists",
				"url" => "/banlist"
			],
			[
				"label" => "Errata",
				"url" => "/errata"
			],
			[
				"label" => "Formats",
				"url" => "/formats"
			],
			[
				"label" => "Races and Traits",
				"url" => "/races"
			],
		]
	],
];

// Add admin links
if (fd_auth()->check("user") > 0) {
  $admin = [
    "Profile" => [
      "url" => "/profile",
      "icon" => "fa fa-user"
    ]
  ];
  $links = $admin + $links;
}

?>
<nav class="navbar navbar-default">
	<div class="container-fluid">

		<!-- Navbar header -->
		<div class="navbar-header">

			<!-- Hamburger -->
      <button
        type="button"
        class="navbar-toggle collapsed"
        data-toggle="collapse"
        data-target="#fowdb-navbar"
      >
        <i class="fa fa-lg fa-bars"></i>
      </button>

			<!-- Brand -->
			<ul>

				<!-- Home -->
				<li>
          <a
            href="http://fowdb.altervista.org"
            class="navbar-brand"
          >
            <i class="fa fa-home fa-lg"></i>
          </a>
        </li>
        
				<!-- Facebook -->
				<li class="visible-xs-inline">
          <a
            href="https://www.facebook.com/fowdb"
            class="navbar-brand"
          >
						<i class="fa fa-lg fa-facebook-square"></i>
					</a>
        </li>
        
      </ul>
      
		</div><!-- /Navbar header -->

		<!-- Navbar content -->
		<div class="collapse navbar-collapse" id="fowdb-navbar">

      <!-- Left navbar -->
			<ul class="nav navbar-nav">
				<?php foreach ($links as $label => &$props): ?>
          <?php if (isset($props["dropdown"])): // Dropdown ?>
						<li class="dropdown">
							<a href="#" class="dropdown toggle" data-toggle="dropdown">
								<i class="<?=$props["icon"]?>"></i>
								<?=$label?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<?php foreach ($props["dropdown"] as &$item): ?>
									<li>
                    <a href="<?=fd_url($item["url"])?>">
                      <?=$item["label"]?>
                    </a>
                  </li>
								<?php endforeach; ?>
							</ul>
						</li>
          <?php else: // Normal link ?>
						<li>
							<a href="<?=fd_url($props["url"])?>">
								<i class="<?=$props["icon"]?>"></i>
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
		<div class="fd-alert alert alert-<?=$alert["type"]?> alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">
				&times;
			</a>
			<div class="fd-alert-content"><?=$alert["message"]?></div>
		</div>  
	<?php endforeach; ?>
</div>

<div id="logo" class="mb-100">
	<a href="<?=fd_url("/")?>">
		<img src="<?=fd_asset("images/logo.png")?>">
	</a>
</div>
