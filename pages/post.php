<?

$errors = false;
$i = 1;

function upload_media($_FILES_name, $new_filename, $type) {
	global $errors;
	global $i;
	echo "<h3>File #$i<br></h3>";
	$i++;
	$move_upload_file_error = NULL;
	// Img upload (png+jpg)
	$tmp_name = $_FILES["$_FILES_name"]["tmp_name"];
	$name = $_FILES["$_FILES_name"]["name"];
	$extension = pathinfo($name, PATHINFO_EXTENSION);
	$new_filename_ext_img = "$new_filename-1500.$extension";
	$new_filename_ext = "$new_filename.$extension";

	// Validation
	if ($type == "png_jpg" && ($extension == "jpg" || $extension == "png")) {
		// Validate width/height
		$width = getimagesize($tmp_name)[0];
		$height = getimagesize($tmp_name)[1];
		if ($width == 1500 && $height == 1500) {
			// Move uploaded file
			if (move_uploaded_file($tmp_name, $new_filename_ext_img)) {

				// Convert ...
				if (file_exists("$new_filename-1500.jpg")) {
					imagepng(imagecreatefromstring(file_get_contents($new_filename_ext_img)), "$new_filename-1500.png");
					unlink("$new_filename-1500.jpg");
				}

				// Add 100x100 png version
				// imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
				function create_resized_image($w_h, $img_type, $new_filename) { // width_height
					$dimensions = imagecreatetruecolor($w_h, $w_h);
					$image = imagecreatefrompng("$new_filename-1500.png");
					imagecopyresampled($dimensions, $image, 0, 0, 0, 0, $w_h, $w_h, 1500, 1500);
					imagepng($dimensions, "$new_filename-$w_h.$img_type", 0);
				}
				create_resized_image(100, "png", $new_filename);
				create_resized_image(1450, "jpg", $new_filename);

				echo "Cover upload successful<br>";

			} else { // move_upload_file failed
				$move_upload_file_error = true;
			}
		} else { // width/height not 1500
			echo "Cover upload failed: File is not 1500x1500 (was $width" . "x$height)<br>";
			$errors = true;
		}
	} elseif ($extension == $type) { // Other extensions
		if (move_uploaded_file($tmp_name, $new_filename_ext)) {
			echo "Cover upload successful<br>";
		} else {
			$move_upload_file_error = true;
		}
	} elseif ($_FILES["$_FILES_name"]["error"] == 4) {
		echo "Cover upload failed. Error code: 4 - No file was uploaded<br>";
	} else { // Unsupported extension
		$errors = true;
		echo "Upload failed: File extension not permitted.<br>";
	}
	if ($move_upload_file_error == true && $_FILES["$_FILES_name"]["error"] != (0 || 4)) {
			echo "Cover upload failed. Error code: " . $_FILES["$_FILES_name"]["error"] . "<br>";
			$errors = true;
	}
}

post_to_sql_string("id");
post_to_sql_string("catalog_id");
post_to_sql_string("artist");
post_to_sql_string("title");
post_to_sql_string("link_soundcloud");
post_to_sql_string("link_youtube");
post_to_sql_string("link_spotify");
post_to_sql_string("link_bandcamp");
post_to_sql_string("link_itunes");
post_to_sql_string("link_beatport");

// Add new track
if (isset($_POST["submit_add"])) {

    // SQL
	$query = "	INSERT INTO tracks
	(catalog_id, artist, title, link_soundcloud, link_youtube, link_spotify, link_bandcamp, link_itunes, link_beatport)
	VALUES ('{$catalog_id}', '{$artist}', '{$title}', '{$link_soundcloud}', '{$link_youtube}', '{$link_spotify}', '{$link_bandcamp}', '{$link_itunes}', '{$link_beatport}');   ";

	$result = mysqli_query($db_connection, $query);
	$id = mysqli_insert_id($db_connection);

	upload_media("cover_psd", "cdn/covers/$id", "psd");
	upload_media("cover_png_jpg", "cdn/covers/$id", "png_jpg");
	upload_media("song_wav", "cdn/songs/$id", "wav");
	upload_media("song_mp3", "cdn/songs/$id", "mp3");

	// Perform SQL query
	if ($result) {
		// Query success
		echo "<br>Database query successful<br>";
	} else {
		// Query failure
		echo "Datatabse query failed. " . mysqli_error($db_connection);
		$errors = true;
	}

	if ($errors == false) {
		redirect_to("/dashboard/tracks?item=$id");
	}
}


// Edit track
elseif (isset($_POST["submit_edit"])) {
	$query = "   UPDATE tracks SET
	catalog_id = '{$catalog_id}',
	artist = '{$artist}',
	title = '{$title}',
	link_soundcloud = '{$link_soundcloud}',
	link_youtube = '{$link_youtube}',
	link_spotify = '{$link_spotify}',
	link_bandcamp = '{$link_bandcamp}',
	link_itunes = '{$link_itunes}',
	link_beatport = '{$link_beatport}'
	WHERE id = '{$id}'   ";

	upload_media("cover_psd", "cdn/covers/$id", "psd");
	upload_media("cover_png_jpg", "cdn/covers/$id", "png_jpg");
	upload_media("song_wav", "cdn/songs/$id", "wav");
	upload_media("song_mp3", "cdn/songs/$id", "mp3");

	$result = mysqli_query($db_connection, $query);
	// Error check
	if ($result) {
		// Success
		echo "<br>Database query successful<br>";
		$id = $_GET["item"];
	} else {
		// Failure
		echo "Datatabse query failed. " . mysqli_error($db_connection) . "<br>";
	}

	if ($errors == false) {
		redirect_to("/dashboard/tracks?item=$id");
	}
}


// Delete track
elseif (isset($_POST["submit_delete"])) {
	$query = "   DELETE FROM tracks WHERE id = {$id} LIMIT 1   ";
	$result = mysqli_query($db_connection, $query);
	// Error check
	if ($result && mysqli_affected_rows($db_connection) == 1) {
		// Success
		echo "Success!<br>";
		function delete_file($path) {
			if (file_exists($path)) {
				unlink($path);
				echo "Deleted $path<br>";
			}
		}
		delete_file("cdn/covers/$id-100.png");
		delete_file("cdn/covers/$id-1000.png");
		delete_file("cdn/covers/$id-1500.png");

		delete_file("cdn/covers/$id-1450.jpg");

		delete_file("cdn/covers/$id.psd");

		delete_file("cdn/songs/$id.mp3");
		delete_file("cdn/songs/$id.wav");
		redirect_to("/dashboard/tracks");
	} else {
		// Failure
		echo "Database query failed. " . mysqli_error($db_connection) . "<br>";
	}

	if ($errors == false) {
		redirect_to("/dashboard/tracks?item=$id");
	}
}


else {
	redirect_to("/dashboard");
}

?>
