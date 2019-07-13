<?

if (isset($_POST["submit_login"])) {
	$password = $_POST["password"];
	if ($password == "passie") {
		$_SESSION["logged_in"] = true;
	} else {
		$_SESSION["logged_in"] = false;
	}
}

session_to_string("logged_in", false);

$logged_out = !$logged_in;

?>
