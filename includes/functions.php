<?

$site_address = "http://" . $_SERVER["HTTP_HOST"];

function get_slug() {
	global $slug;
	$slug = $_SERVER["REQUEST_URI"];
	$slug = explode("?", $slug, 2);
	$slug = $slug[0];
	$slug = strtolower($slug);
	return $slug;
}

function redirect_from_to($from_slug, $to_slug) {
	global $slug;
	global $site_address;
	if ($slug == $from_slug) {
		header("Location: $site_address$to_slug");
		die();
	}
}
function redirect_if() {
	redirect_from_to("/home", "");
}
function redirect_to($to_slug) {
	global $site_address;
	echo "$site_address";
	header("Location: $site_address$to_slug");
	die();
}

function db_connect() {
	global $db_connection;
	$host = "localhost";
	$username = "web";
	$password = "hO1oj24kUdEtvDXe";
	$db_name = "lacuna_records";
	$db_connection = mysqli_connect($host, $username, $password, $db_name);
}
function db_disconnect() {
	// 5. Disconnect from db
	global $db_connection;
	mysqli_close($db_connection);
}
function db_query($query) {
	global $db_connection;
	$query = mysqli_real_escape_string($db_connection, $query);
	$result = mysqli_query($db_connection, $query);
	return $result;
}

function validate_upload_image($target_file) {
	// Check if file already exists
	if (file_exists($target_file)) {
		die("File already exists");
	}
	// Validate file size
	if ($_FILES["cover_upload"]["size"] > 5000000) {
		die("File is too large.");
	}
	// Validate format
	global $imageFileType;
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
	}
}

function encode_for_slug($string) {
	$string = strtolower($string);
	$string = str_replace([" "], "-", $string);
	$string = str_replace([",", "'", '"'], "", $string);
	return $string;
}

function session_to_string($session_index, $else_val) {
	if (isset($_SESSION["$session_index"])) {
		$session_value = $_SESSION["$session_index"];
		global $$session_index;
		$$session_index = $session_value;
	} else {
		global $$session_index;
		$$session_index = $else_val;
	}
}
function post_to_sql_string($post_index) {
	if (isset($_POST["$post_index"])) {
		$post_value = $_POST["$post_index"];
		global $db_connection;
		$post_value = mysqli_real_escape_string($db_connection, $post_value);
		global $$post_index;
		$$post_index = $post_value;
	}
}

?>
