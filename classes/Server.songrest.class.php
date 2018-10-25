<?php
Class Server {
	public $server_config;
	public $master_addr_ext;
	public $master_addr_int;
	public $slave_addr_ext;
	public $slave_addr_int;

	function __construct() {
		$this->server_config = 'normal';
		$this->master_addr_ext = '192.185.171.182';
		$this->master_addr_int = 'localhost:3306';
		$this->slave_addr_ext  = '192.185.171.182';
		$this->slave_addr_int  = 'localhost:3306';
	}
}
?>
