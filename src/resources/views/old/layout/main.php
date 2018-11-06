<!DOCTYPE html>
<html lang="en" prefix="<?=$ogp->getHtmlPrefix()?>">
<head>
	<!-- Facebook tags -->	
	<meta property="fb:app_id" content="<?=config('facebook.id')?>">

	<!-- Open Graph Project meta tags -->
	<?=$ogp->toHtml()?>

	<!-- Title -->
	<title><?=$ogp->title()?></title>

	<!-- Other meta tags -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- CSS -->
	<style>
		a,abbr,acronym,address,applet,article,aside,audio,b,big,blockquote,body,canvas,caption,center,cite,code,dd,del,details,dfn,div,dl,dt,em,embed,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,label,legend,li,mark,menu,nav,object,ol,output,p,pre,q,ruby,s,samp,section,small,span,strike,strong,sub,summary,sup,table,tbody,td,tfoot,th,thead,time,tr,tt,u,ul,var,video{margin:0;padding:0;border:0;font:inherit;vertical-align:baseline}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:after,blockquote:before,q:after,q:before{content:'';content:none}table{border-collapse:collapse;border-spacing:0}
	</style>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css" defer>
	<script src="https://use.fontawesome.com/f5164b39df.js" defer></script>
	<link
		href="<?=asset('css/app.min.css', 'css')?>"
		rel="stylesheet"
		type="text/css"
		defer
	>

	<!-- Favicon -->
	<link
		rel="icon"
		type="image/png"
		href="<?=asset('favicon.ico', 'png')?>"
	>
</head>

<body>
	<a name="top"></a>
	<div class="container-fluid" id="page-wrapper">
		<?php require path_views('old/includes/header.php'); ?>
		<?=$pageContent?>
	</div>
	<?php require path_views('old/includes/footer.php'); ?>

	<!-- JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js" defer></script>

	<?php if (isset($options['jqueryui'])): // jQuery UI ?>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css" defer>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" defer></script>
	<?php endif; ?>

	<?php if (isset($options['lightbox'])): // Lightbox ?>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/css/lightbox.min.css' defer>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/js/lightbox.min.js' defer></script>
	<?php endif; ?>

  <!-- Bootstrap -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" defer></script>

  <!-- Common script -->
  <script src="<?=asset('js/public/common.min.js', 'js')?>" defer></script>

	<!-- Optional scripts -->
	<?php if (isset($options['js'])): ?>
		<?php foreach ($options['js'] as &$link): ?>
			<script src="<?=asset("js/{$link}.min.js", 'js')?>" defer></script>
		<?php endforeach; ?>
	<?php endif; ?>
</body>
</html>
