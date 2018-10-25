<?php
require_once("includes/top.php");
require_once("{$pathTop}/includes/database.php");

$userid = (isset($_GET['userid'])) ? $_GET['userid'] : null;
if(is_null($userid)) {
	header("Location: {$pathTop}/login.php");
	exit;
}

// get trials for this researcher from database.php, including count
$userInfo = getInitialData("researcher", $userid);

?>
<!DOCTYPE html>
<html>
<head>
	<?php require_once("{$pathTop}/includes/head.php") ?>
	<link rel="stylesheet" type="text/css" media="screen" href="css/researcher.css" />
	<script src="researcher_interface.js"></script>

	<title>Researcher</title>
</head>
<body>
	
</body>
</html>