<?php
require_once("../classes/DB.songrest.class.php");
$db = new DB;
$userid = isset($_GET['userid']) ? $_GET['userid'] : 0;
$response = array(
	'status' => 'success',
	'data' => array()
);
if($userid == 0) {
	$response['status'] = "No user ID provided.";
} else {
	// get user's data from database
	$sql = "SELECT p.*, m.*
		FROM person p
		INNER JOIN playlist b ON p.userid = b.userid
		INNER JOIN music m ON m.musicid = b.musicid
		WHERE p.userid = {$userid}";
	$result = $db->query($sql);
	if($result && count($result)) {
		foreach($result as $row) {
			if(!isset($response['data']['user'])) {
				$response['data']['user'] = array(
					'userid' => $row['userid'],
					'firstname' => $row['firstname'],
					'lastname' => $row['lastname']
				);
				$response['data']['playlist'] = array();
			}
			$response['data']['playlist'] []= array(
				'musicid' => $row['musicid'],
				'title' => $row['title'],
				'artist' => $row['artist'],
				'style' => $row['style'],
				'duration' => $row['duration'],
				'filename' => $row['filename']
			);
		}
	}
}
echo json_encode($response);
?>