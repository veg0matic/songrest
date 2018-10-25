<?php
require_once("DB.songrest.class.php")

Class User {
	public $error;
	public $debug = false;
	public $userid;
	public $userType;
	public $confirmed;
	public $db;
	public $tableRefs = array(
		"j" => "assessment",
		"a" => "assignment",
		"s" => "subject",
		"f" => "audio_file",
		"c" => "audio_category",
		"t" => "trial",
		"m" => "modification",
		"p" => "pulse",
		"r" => "researcher",
		"l" => "session",
		"w" => "tone",
		"ac" => "audio_category_bridge",
		"am" => "audio_modification_bridge",
		"rt" => "researcher_trial_bridge"
	);
	public $tables = array(
		'subject' => array("a", "s", "f", "c", "t"),
		'researcher' => array("a", "s", "f", "c", "t", "j"),
		'admin' => array("a", "s", "f", "c", "t", "j", "r", "l"),
		'uberadmin' => array("a", "s", "f", "c", "t", "m", "p", "w")
	);

	function __construct($userid, $userType){
		$this->userid = $userid;
		$this->userType = $userType;
		$this->db = new DB;
	}
	function __destruct(){
		$this->userid = null;
		$this->userType = null;
		$this->confirmed = false;
		$this->db = null;
	}

	protected function getData() {
		switch($this->userType) {
			case "subject":
				return subjectData();
				break;
			case "researcher":
				return researcherData();
				break;
			case "admin":
				return adminData();
				break;
			case "uberadmin":
				return uberadminData();
				break;
			default:
				// error?
		}
	}

	protected function subjectData() {
		// ultimately get field names by permissions in user table
		$sql = "SELECT a.*, s.*, f.*, c.*
			FROM assignment a 
			INNER JOIN subject s ON s.subjectid = a.subjectid
			INNER JOIN audio_file f ON f.audioid = a.audioid
			INNER JOIN audio_category_bridge ac ON ac.audioid = a.audioid
			INNER JOIN audio_category c ON c.categoryid = ac.categoryid
		WHERE 
			(a.active + s.active + f.active + c.active) = 4 
			AND s.subjectid = {$this->userid}";
		return $this->db->query($sql);
	}

	protected function researcherData($list) {

	}

	protected function adminData($list) {

	}

	protected function uberadminData($list) {

	}

	// "t,r|t->rt>t.trialid->r>rt.researcherid|researcherid={$userid}"

		$statement = "SELECT ";
		list($select, $from, $where) = explode("|", $queryDef, 3);
		$tables = explode(",", $select);
		for($i = 0; $i < count($tables); $i++) {
			$tables[$i] .= '.*';
		}
		$statement .= implode(", ", $tables);
		$joins = explode("->", $from);
		$statement .= " FROM " . $tableRefs[$joins[0]] . " {$joins[0]}\n";
		if(count($joins) > 1) {
			// these are the actual joins
			for($i = 1; $i < count($joins); $i++) {
				$thisJoin = $joins[$i];
				$joinWith
			$statement .= "INNER JOIN ";
		}
	}
}
?>