<?php
require_once("Server.songrest.class.php");

Class DBConn {
	public $dbhost;
	public $dbpass;
	public $dbuser;
	public $dbname;

	function __construct() {
		$server = new Server;
		$hostname = php_uname("n");
		if(strpos($hostname,'songrest') !== false) {
			$this->dbhost = $server->master_addr_int;
		} else {
			$this->dbhost = $server->master_addr_ext;
		}
		$this->dbpass = 'D1yJsP1a4er';
		$this->dbuser = 'song2_system';
		$this->dbname = 'song2_research';
	}
}
?>
