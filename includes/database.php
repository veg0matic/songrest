<?php
require_once("../classes/DB.songrest.class.php");
$db = new DB;
$tableRefs = array(
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

function authenticateUser($userName, $passwd) {
	global $db;
	$returnArray = array(
		'status' => false,
	);
	$sql = "SELECT * FROM user
		WHERE active = 1 AND username = '{$userName}' AND password = '{$passwd}'";
	$result = $db->query($sql);
	if($result && count($result)) {
		$row = $result[0];
		$returnArray = array(
			'status' => true,
			'userName' => $row['username'],
			'userid' => $row['userid'],
			'userType' => $row['type']
		);
	}
	return json_encode($returnArray);
}

// global DB management functions
function getInitialData($userType, $userid) {
	switch($userType) {
		case 'subject':
			return getSubjectData($userid);
			break;
		case 'researcher':
			return getResearcherData($userid);
			break;
		case 'admin':
			return getAdminData($userid);
			break;
		case 'uberadmin':
			return getUberadminData($userid);
			break;
	}
}

function getSubjectData($userid) {
	global $db;

}

/**
 * @param $userid
 * @return array
 */
function getResearcherData($userid) {
	global $db;
	// tables: trial, assignment, subject, audio_file, category, assessment
	// get trials for this researcher
	$returnArray = array();
	$researcher = $trials = $trialList = array();
	$result = getData("t,r|t->rt(trialid)->r(researcherid)|researcherid={$userid}");
	// "t,r|t->rt(trialid)->r(researcherid)|researcherid={$userid}"
	$sql = "SELECT t.*, r.*
		FROM trial t
		INNER JOIN researcher_trial_bridge rt ON rt.trialid = t.trialid
		INNER JOIN researcher r ON r.researcherid = rt.researcherid
		WHERE t.active = 1 AND r.active = 1 AND r.researcherid = {$userid}";
	$result = $db->query($sql);
	if($result && count($result)) {
		foreach($result as $row) {
			$trialid = $row['trialid'];
			if(!in_array($trialid, $trialList)) $trialList[] = $row['trialid'];
			if(!array_key_exists($trialid, $trials)) {
				$trials[$trialid] = array(
					"trial_name" => $row['trial_name'],
					"purpose" => $row['purpose'],
					"creation_date" => $row['creation_date']
				);
			}
			if(!count(array_keys($researcher))) {
				$researcher = array(
					"prefix" => $row['prefix'],
					"first_name" => $row['first_name'],
					"last_name" => $row['last_name'],
					"suffix" => $row['suffix'],
					"affiliation" => $row['affiliation']
				);
			}
		}
	}
	if(count($trialList) == 1) {
		// given the selected trial, acquire the next batch of data

	} else if(count($trialList) > 1) {
		// this is all the data that's needed for now; after researcher selects a trial, the next batch will be acquired
		$returnArray = array("researcher" => $researcher, "trials" => $trials, "trialList" => $trialList);
	}
}

function getAdminData($userid) {
	global $db;

}

function getUberadminData($userid) {
	global $db;

}

function getData($queryDef) {
	global $db, $tableRefs;
	// "t,r|t->rt>trialid->r>researcherid|researcherid={$userid}"

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
/*
	$sql = "SELECT t.*, r.*
		FROM trial t
		INNER JOIN researcher_trial_bridge rt ON rt.trialid = t.trialid
		INNER JOIN researcher r ON r.researcherid = rt.researcherid
		WHERE t.active = 1 AND r.active = 1 AND r.researcherid = {$userid}";
*/
}
?>
