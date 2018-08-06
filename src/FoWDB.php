<?php

namespace App;

class FoWDB {

	/**
	 * Deletes all files from a directory, no recursion (?)
	 *
	 * @param string $path The directory to be emptied, starting from root
	 * @return string The directory that was emptied
	 */
	public static function deleteAll($path) {

		// Erase first slash, if any
		if (substr($path, 0, 1) == '/') {
			$path = substr($path, 1);
		}

		// Erase last slash, if any
		if (substr($path, -1) == '/') {
			$path = substr($path, 0, -1);
		}

		// Delete all files in provided directory
		array_map('unlink', glob("{$_SERVER['DOCUMENT_ROOT']}/{$path}/*"));

		// Return path, no root included
		return $path;
	}


	/**
	 * Process uploaded image
	 *
	 * This function get the $_FILES array of uploaded file, processes it,
	 * Then saves to default path or to provided one, if present.
	 * If $pass_temp is TRUE, return the tmp_name of file for further processing, no saving.
	 *
	 * ERRORS:
	 * 1 --> No file was uploaded
	 * 2 --> Uploaded file has error
	 * 3 --> File larger than 2 Mb
	 * 4 --> File extension not allowed
	 *
	 * @param array Reference to $_FILES array
	 * @param string Destination filename or default filename if NULL
	 * @param bool Whether to return temporary path (TRUE) or save it (FALSE)
	 * @return mixed Integer for errors on failure (see above), destination filename as string, array for temporary uploaded files
	 */
	public static function uploadImage (&$file, $dest = NULL, $pass_temp = FALSE) {

		// Check if there's an uploded file
		if (is_array($file) AND isset($file['name'])) {

			// Check for errors
			if (!$file['error']) {

				// Check if file size is less than 2 Mb
				if ($file['size'] < 2*1024*1024) {

					// Check if file is an image of allowed extension
					if (
						in_array(
							$file['type']
							,['image/jpeg', 'image/jpg', 'image/png']
						)
					) {

						// Return temporary filename if requested by user
						if ($pass_temp) {
							return $file;
						}

						// Check if no destination filename was passed
						if (!isset($dest)) {

							// Set default filename
							$dest = '_images/uploads/' . // Directory
									$file['name']; // File name
						}

						// Check if file already exists
						if (file_exists($dest)) {

							// Get file extension of destination filename
							$extension = strtolower(pathinfo($dest, PATHINFO_EXTENSION));

							// Append timestamp like _YYYMMDD-HHMMSS to filename
							$dest = str_replace(
								".{$extension}"
								,"_{date('Ymd-Gis')}.{$extension}"
							);
						}

						// Move file to destination filename
						move_uploaded_file($file['tmp_name'], $dest);

						// Return destination filename
						return $dest;
					}
					// File extension not allowed
					else { return 4; }
				}
				// File size larger than 2 Mb
				else { return 3; }
			}
			// Uploaded file has an error
			else { return 2; }
		}
		// No file uploaded
		else { return 1; }
	}


	/**
	 * Resizes uploaded image [,applies watermark, crops to specific size]
	 *
	 * @param array $file Reference to $_FILES array of uploaded file
	 * @param string $dest_fname Destination filename or default filename if it's NULL
	 * @param string $quality Quality of final image, 'lq' for low or 'hq' for high
	 * @param bool Decide if apply watermark, default is FALSE
	 * @param array $crop Must be assoc array: 'x', 'y', 'width', 'height' keys, order required
	 * @return mixed The filename of processed file as string or FALSE on failure
	 */
	public static function processImage(
		$file,
		$dest_fname = NULL,
		$quality = 'hq',
		$apply_watermark = FALSE,
		$crop = NULL
	) {

		// Check if file is a $_FILES array
		if (isset($file['error']) AND $file['error'] == 0)  {

			// Get extension of uploaded file
			$uploaded_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

			// Check if destination was not passed
			if(!isset($dest_fname)) {

				// Set default destination
				$dest_fname = "_images/uploads/{$quality}/" . // Directory
								pathinfo($file['name'], PATHINFO_FILENAME) . // Filename
								"_" . date('Ymd-Gis'). // Timestamp
								".jpg"; // Extension
			}

			// If destination was passed, make sure jpg extension is used anyway
			else {
				$dest_fname = str_replace(
					pathinfo($file['name'], PATHINFO_EXTENSION)
					,'jpg'
					,$dest_fname
				);
			}

			// Set dimensions and watermark filename to be used based on requested quality
			switch($quality) {
				case 'lq':
					list($dest_w, $dest_h) = [280, 391];
					$watermark_fname = $_SERVER['DOCUMENT_ROOT'] .
						'/_images/watermark/watermark280.png';
					break;
				case 'hq':
				default:
					list($dest_w, $dest_h) = [480, 670];
					$watermark_fname = $_SERVER['DOCUMENT_ROOT'] .
						'/_images/watermark/watermark480.png';
					break;
			}

			// Get dimensions of uploaded file
			list($uploaded_w, $uploaded_h) = getimagesize($file['tmp_name']);

			// Get dimensions of watermark to be used
			list($watermark_w, $watermark_h) = getimagesize($watermark_fname);

			// Load resized/watermarked image
			$dest = imagecreatetruecolor($dest_w, $dest_h);

			// Load watermark to be applied
			$watermark = imagecreatefrompng($watermark_fname);

			// Load uploaded file (.jpeg, .jpg or .png)
			$uploaded = imagecreatefromstring(file_get_contents($file['tmp_name']));

			// Check if crop is requested and matches with required pattern
			if (
				isset($crop) AND
				!empty($crop) AND
				array_keys($crop) === ['x', 'y', 'width', 'height']
			) {

				// Create blank black image of crop size
				$crop_img = imagecreatetruecolor($crop['width'], $crop['height']);

				// Fill it with white
				imagefill(
					$crop_img
					,0,0
					,imagecolorallocate($crop_img, 255, 255, 255)
				);

				/*
				 * Crop initial image and copy it into blank image
				 *
				 * This is necessary to avoid black corners on card images
				 * Which do not match the website template
				 */
				if (
					imagecopy(
						$crop_img
						,$uploaded
						,0, 0
						,$crop['x'], $crop['y']
						,$crop['width'], $crop['height']
					)
				) {

					// Set the cropped image to the uploaded to continue processing
					$uploaded = $crop_img;

					// Change dimensions of uploaded file
					list($uploaded_w, $uploaded_h) = [$crop['width'], $crop['height']];
				}

				// Couldn't resize
				else {
					return FALSE;
				}
			}

			// Try to resize
			if (
				imagecopyresampled(
					$dest
					,$uploaded
					,0, 0
					,0, 0
					,$dest_w, $dest_h
					,$uploaded_w, $uploaded_h
				)
			) {

				// Check if user requested to apply watermark
				if ($apply_watermark) {

					// Try to apply watermark
					if (
						!imagecopy(
							$dest
							,$watermark
							,0, 0
							,0, 0
							,$watermark_w, $watermark_h
						)
					) {
						// Couldn't apply watermark
						return FALSE;
					}
				}

				// Save image to destination path
				imagejpeg($dest, $dest_fname, 75);

				// Empty memory from images
				imagedestroy($dest);
				imagedestroy($watermark);
				imagedestroy($uploaded);

				// Return destination filename
				return $dest_fname;
			}

			// Couldn't resize
			else {
				return FALSE;
			}
		}

		// No file array provided
		else {
			return FALSE;
		}
	}

	/**
	 * Notify the user
	 *
	 * @param string Message to be shown
	 * @param string Notification colors:
	 */
	public static function notify($message, $type = 'info') {

		if (session_status() == PHP_SESSION_NONE) {
    		session_start();
		}

		if (!in_array($type, ['success', 'info', 'warning', 'danger'])) {
			$type = 'info';
		}

		$_SESSION['notif'] = $message;
		$_SESSION['notif-type'] = $type;
	}


	/**
	 * Renders the HTML presentation of a card's text
	 *
	 * The rendered string has HTML <img> for will and rest symbols,
	 * CSS classes for abilities, skills, breaks and horizontal rules.
	 *
	 * @param string $txt The string to be rendered
	 * @return string $txt The rendered string
	 */
	public static function renderText ($txt) {

		// Import attributes helper Ex.: w => Light
		$attributes = \App\Helpers::get('attributes');

		// String replacements
		$replace_str = [
			// Rest
			'{rest}' => '<img src="_images/icons/1x1.gif" class="fdb-icon-rest" />'
			// => automatic abilities arrow
			,'=>' => '&rArr;'
			// Errata: word in rulings
			,'Errata:' => '<span class="ruling-errata">Errata:</span>'
			// Double <br>
			,'<hr>' => "<div class='fdb-separator-v-05'></div>"
			,'[(' => '【'
			,')]' => '】'
		];

		// Regex replacements
		$replace_regex = [
			// Will symbols
			"/{([wrugbmvt])}/" => "<img src='_images/icons/1x1.gif' class='fdb-icon-$1' alt='$1'/>"
			// Free will
			,"/{([\dx]+)}/" => "<div class='fdb-icon-free'>$1</div>"
			// Symbol skills
			,"/\[_(.+?)_\]/" => "<span class='mark_skills'>$1</span>"
			// Break
			,"/^\[Break\](.+?)(<br>|$)/" => "<span class='mark_break'>Break</span> <span class='mark_breaktext'>$1</span><br>"
			// Abilities (white text on black, legacy)
			,"/\[(J-Activate|Continuous|Activate|Target Attack|Enter|Flying|Explode|Pierce|Trigger|First Strike|Imperishable|Swiftness|Awakening|Incarnation|Quickcast|Remnant|Stealth|Judgment|Evolution|Shift)\]/" => "<span class='mark_abilities'>$1</span>"
			// -ERRATA- on card text
			,"~-errata-(.+?)-/errata-~" => "<span class='mark_errata'>$1</span>"
		];

		// Perform regex string replacements
		$txt = preg_replace(array_keys($replace_regex), $replace_regex, $txt);

		// Perform simple string replacements
		$txt = str_replace(array_keys($replace_str), $replace_str, $txt);

		// Return string
		return $txt;
	}


	/**
	 * Pads right
	 */
	public static function padRight($str, $length, $char = '0') {

		if (strlen($str) >= $length) { return $str; }
		while (strlen($str) < $length) { $str = $str . $char; }
		return $str;
	}


	/**
	 * Pads left
	 */
	public static function padLeft($str, $length, $char = '0') {

		if (strlen($str) >= $length) { return $str; }
		while (strlen($str) < $length) { $str = $char . $str; }
		return $str;
	}
}
