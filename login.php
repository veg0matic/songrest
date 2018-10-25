<?php
require_once("includes/top.php");
require_once "{$pathTop}/includes/database.php";

$cookieCheck = isset($_COOKIE['songrest_memory']);
$postCheck = isset($_POST['submit']);
$errorMessage = '';
$userName = '';
$passwd = '';

if($cookieCheck) {
	// Parse the cookie if set
	list($userName, $userid, $userType) = explode("||", $_COOKIE['songrest_memory'], 4);
}

if($postCheck) {
	$userName = $_POST['username'];
	$passwd = $_POST['passwd'];
	$authCheck = authenticateUser($userName, $passwd);
	if($authCheck['status']) {
		$userid = $authCheck['userid'];
		$userType = $authCheck['userType'];
		setcookie("songrest_memory", "{$userName}||{$userid}||{$userType}", time() + 86400, "/");
		// login process ending in redirect
		$location = $userType.'_interface.php';
		header("Location: {$location}?userid={$userid}");
	} else {
		$errorMessage = "Login failed.";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<?php require_once("{$pathTop}/includes/head.php") ?>
	<link rel="stylesheet" type="text/css" media="screen" href="css/researcher.css" />
	<script src="researcher_interface.js"></script>

	<title>Trial Management</title>
</head>
<body>
	<h2>Please log in</h2>
	<form method="POST" action="<?php echo "{$pathTop}/login.php"; ?>">
		<label for="username"><input type="text" name="username" id="username" value="<?php echo $userName; ?>"></label>
		<label for="password"></label><input type="text" name="password" id="password" value="">
		<button name="submit" type="submit" value="Log in"></button>
		<input type="text" name="error" id="error" value="<?php echo $errorMessage; ?>">
	</form>

</body>
</html>
