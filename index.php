<?
	session_start();
	include("./includes/functions.php");
	include("./includes/check_login.php");
	get_slug();
	db_connect();

	if ($slug == "/") {
		$include_path = "pages/login.php";
	} elseif ($logged_out) {
		redirect_to("/dashboard");
	} elseif (file_exists("pages$slug.php")) {
		$include_path = "pages$slug.php";
	} elseif (file_exists("pages$slug/index.php")) {
		$include_path = "pages$slug/index.php";
	} else {
		redirect_to("/dashboard");
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/css/home.css?r=<?=rand(0,999)?>">
		<link rel="stylesheet" type="text/css" href="/css/jquery.jscrollpane.css">
	</head>
	<body>
		<? include("$include_path"); ?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="/js/autoresize.js"></script>
		<script src="/js/main.js?r=<?=rand(0,999)?>"></script>
	</body>
</html>
<? db_disconnect(); ?>
