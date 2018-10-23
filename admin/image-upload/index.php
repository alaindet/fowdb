<?php

if (admin_level() > 0) {
	// Process uploaded file
	if(
		isset($_POST['submit']) AND
		$_POST['submit'] == 'Send image(s)' AND
		isset($_FILES['files'])
	) {
		// Get $_FILES labels
		$labels = array_keys($_FILES['files']);

		// Transpose $_FILES
		$uploads = array();
		for($i = 0, $len = count($_FILES['files']['name']); $i < $len; $i++) {
			foreach ($labels as &$label) {
				$uploads[$i][$label] = $_FILES['files'][$label][$i];
			}
		}

		// Get watermark flag from user
		$watermark = isset($_POST['watermark']) ? true : false;

		// Print image links container
		echo '<div class="well"><h3>Images processed to:</h3>';

		// Loop through uploaded images
		foreach ($uploads as &$upload) {

			// Init relative path
			$relativePath = "";

			// Build path
			if (isset($_POST['cluster-set']) AND !empty($_POST['cluster-set'])) {
				
				// Replace dot with slash
				$relativePath = str_replace(".", "/", $_POST['cluster-set']) . "/";

				// Create folder at that path, if not existing
				\App\Legacy\FileSystem::createDirectory(
					path_root("images/uploads/hq/{$relativePath}"), true
				);
				\App\Legacy\FileSystem::createDirectory(
					path_root("images/uploads/lq/{$relativePath}"), true
				);

			}

			// Assemble final absolute paths
			$hq_path = path_root('images/uploads/hq/'.$relativePath.$upload['name']);
			$lq_path = path_root('images/uploads/lq/'.$relativePath.$upload['name']);

			// Create image
			$img = \Intervention\Image\ImageManagerStatic
		        ::make($_FILES['cardimage'])
		        ->resize(480, 670)
		        ->insert(path_root('images/watermark/watermark480.png'))
		        ->save($hq_path, 80);

			// Create thumbnail image
			$imgThumb = \Intervention\Image\ImageManagerStatic
		        ::make($_FILES['cardimage'])
		        ->resize(280, 391)
		        ->insert(path_root('images/watermark/watermark280.png'))
		        ->save($lq_path, 80);

			// Echo image link
			echo "<p>
					<a href='{$path_hq}' target='_blank'>
						<button type='button' class='btn btn-success btn-xs'>
							<span class='glyphicon glyphicon-new-window'></span>
						</button>
						HQ: {$path_hq}
					</a>
					<br>
					<a href='{$path_lq}' target='_blank'>
						<button type='button' class='btn btn-success btn-xs'>
							<span class='glyphicon glyphicon-new-window'></span>
						</button>
						LQ: {$path_lq}
					</a>
				</p>
				<hr>";
		}

		// Close image links container
		echo '</div>';
	}

	// Empty uploads folder
	if (
		isset($_POST['submit']) AND
		$_POST['submit'] == 'Empty uploads folder'
	) {

		// Delete all files into uploads directories
		$dirs = [
			"images/uploads/hq",
			"images/uploads/lq"
		];

		echo "<p><strong>Emptied folder(s):</strong><br />";
		foreach($dirs as &$dir) {
			\App\Legacy\FileSystem::emptyDirectory($dir);
			echo "/" . $dir . '<br />';
		}
		echo '</p>';
	}

	// Get sets from database
	$clusters = \App\Legacy\Helpers::get('clusters');

	// Include form to upload image
	include 'admin/upload-image/form.html.php';
}
else {
	// Request login
	echo '<div class="well">Please <a href="index.php?p=admin"><strong>login</strong></a></div>';
}
