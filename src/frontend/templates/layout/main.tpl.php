<?php

// VARIABLES
// $fd_content
// $fd_ogp
// $scripts
// $state
// $title
// $token
// dependencies: jqueryui, lightbox

?>
<!DOCTYPE html>
<html lang="en" prefix="<?=$fd_ogp->getHtmlPrefix()?>">
<head>

  <!-- Meta tags -->
	<?=$fd_ogp->toHtml()?>
	<meta property="fb:app_id" content="<?=fd_config("facebook.id")?>">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

  <?php if (isset($token)): // Anti-CSRF token ?>
    <meta name="_token" content="<?=$token?>">
  <?php endif; ?>

  <!-- Reset CSS -->
	<style>
		a,abbr,acronym,address,applet,article,aside,audio,b,big,blockquote,body,canvas,caption,center,cite,code,dd,del,details,dfn,div,dl,dt,em,embed,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,label,legend,li,mark,menu,nav,object,ol,output,p,pre,q,ruby,s,samp,section,small,span,strike,strong,sub,summary,sup,table,tbody,td,tfoot,th,thead,time,tr,tt,u,ul,var,video{margin:0;padding:0;border:0;font:inherit;vertical-align:baseline}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:after,blockquote:before,q:after,q:before{content:'';content:none}table{border-collapse:collapse;border-spacing:0}
	</style>
 
  <!-- Bootstrap -->
 	<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" defer>

  <!-- Fontawesome 4.7 -->
	<script src="https://use.fontawesome.com/f5164b39df.js" defer></script>

  <!-- My CSS -->
	<link href="<?=fd_asset("assets/css/app.min.css", "css")?>" rel="stylesheet" type="text/css" defer>

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?=fd_asset("favicon.ico", "png")?>">

  <!-- Title -->
	<title><?=$title?></title>
</head>

<body>
	<a name="top"></a>
  <header><?=fd_include_template("includes/header")?></header>
  <main><?=$fd_content?></main>
  <footer><?=fd_include_template("includes/footer")?></footer>

  <!-- jQuery 2.2.4 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js" defer></script>

  <?php
    // Dependency: jQuery UI 1.11.4
    if (isset($dependencies['jqueryui'])):
  ?>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css" defer>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" defer></script>
	<?php endif; ?>

  <?php
    // Dependency: Lightbox 2.10.0
    if (isset($dependencies['lightbox'])):
  ?>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/css/lightbox.min.css' defer>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/js/lightbox.min.js' defer></script>
	<?php endif; ?>

  <!-- Bootstrap -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js" defer></script>

  <!-- Application initial state -->
  <script>
    window.INITIAL_STATE = <?=json_encode(
      $state,
      JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES
    )?>;
    window.BASE_URL = "<?=fd_config("app.url")?>";
    window.APP = {};
  </script>

  <!-- My scripts -->
  <?php foreach ($scripts as $script): ?>
    <script src="<?=fd_asset("assets/js/{$script}.min.js", 'js')?>" defer></script>
  <?php endforeach; ?>
</body>
</html>
