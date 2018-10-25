<?php

Class Researcher extends User {


	private function getInitialData($userid) {
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

}


?>