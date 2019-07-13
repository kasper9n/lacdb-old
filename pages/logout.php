<?

$_SESSION["logged_in"] = false;
session_to_string("logged_in");
$logged_out = !$logged_in;
redirect_to("/");

?>
