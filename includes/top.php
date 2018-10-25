<?php
// $pathTop must be used for all includes
$pathTop = $_SERVER["DOCUMENT_ROOT"];
@session_start();
if(!isset($_SESSION['logged']) || $_SESSION['logged'] == 0) {
	header("{$pathTop}/login.php");
	exit;
}
$currentUser = $_SESSION['currentUser'];
?>