<?php
/**
 * @author stevescode
 * @link http://www.phpdoc.org/docs/latest/index.html
 * includes functions for checking bulb status, checking if there's movement, turning off bulbs, and some logic to bring these things together
 * to perform actions
 * 
 */

include 'vars.php';

/* LOCAL VARS */
$timeToLightsOff = 900; // time period to wait (in seconds) before switching off lights
$arrayOfRooms = array(
	'lounge',
	'upstairslanding',
	'kitchen',
	'downstairshall',
	'nursery',
	'garage',
	'frontbedroom',
);
$loggingStart = date('Y/m/d H:i:s');
$loggingEnd = PHP_EOL;
/* LOCAL VARS END */

// Checks whether a bulb is on
function checkBulb($theBulb) {
	global $hueApi, $hueIp;

	$url = "http://".$hueIp."/api/".$hueApi."/lights/".$theBulb."/";
	$json = file_get_contents($url);
	$data = json_decode($json);

	if ($data->state->on == 1) {
		return 1;
	}
	else {
		return 1;
	}
}

// Checks whether there is movement stored in a the database for a specific room
function checkMovement($room, $delay) {

	global $servername, $username, $password, $dbname;
	$period = date('U') - $delay;

	$mysqli = new mysqli($servername, $username, $password, $dbname);
	$result = $mysqli->query("SELECT * FROM activity WHERE timestamp > ".$period." AND sensor = '".$room."'");

	if ($mysqli->affected_rows > 0) {
		return 1;
	}
	else {
		return 0;
	}
}

// Switches off a light
function switchOff($theBulb) {
	global $hueApi, $hueIp;

	// build the payload
	$arrData['on'] = false;
	$data = json_encode($arrData);

	$url = "http://".$hueIp."/api/".$hueApi."/lights/".$theBulb."/state/";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

	$response = curl_exec($ch);
	echo $response; 
}

// Translates a room into specific bulb identifiers
function identifyBulbs($theRoom) {
	/// 5,6,7 - patio
	if ($theRoom == 'lounge') {
		$theBulb = array(8,9);
		return $theBulb;
	}
	elseif ($theRoom == 'kitchen') {
		$theBulb = 3;
		return $theBulb;
	}
	elseif ($theRoom == 'downstairshall') {
		$theBulb = array(1,4);
		return $theBulb;
	}
	elseif ($theRoom == 'upstairslanding') {
		$theBulb = 10;
		return $theBulb;
	}
	elseif ($theRoom == 'nursery') {
		$theBulb = 2;
		return $theBulb;
	}
	elseif ($theRoom == 'garage') {
		$theBulb = null;
		return $theBulb;
	}
	elseif ($theRoom == 'frontbedroom') {
		$theBulb = null;
		return $theBulb;
	}
}

function logToDisk($data) {
		$localfile = fopen('logs/logs.log', 'a');
		fwrite($localfile, $data);
		fclose('logs/logs.log');
}

foreach ($arrayOfRooms as $room) {
	if (checkMovement($room, $timeToLightsOff) == 1) {
		// We've found movement for this $room stored in the database, we do nothing
		$log = $loggingStart.' Database check shows movement, doing nothing '.$loggingEnd;
		logToDisk($log);
	}
	else {
		// We've not found movement for this $room within $timeToLightsOff, let's take action
		// Let's just call this function once and store the result in a var

		$returnedBulbs = identifyBulbs($room);

		if (is_array($returnedBulbs)) {
			// There's more than one bulb in this room, so we need to go through each one
			foreach ($returnedBulbs as $bulb) {
				if (checkBulb($bulb) == 1) {
					$log = $loggingStart.' Turning off bulb '.$bulb.' in '.$room.' '.$loggingEnd;
					logToDisk($log);
					
					switchOff($bulb);
				}
			}
		}
		elseif (is_int($returnedBulbs)) {
			echo $returnedBulbs.' - '.$room;
			$log = $loggingStart.' Turning off bulb '.$returnedBulbs.' in '.$room.' '.$loggingEnd;
			logToDisk($log);

			switchOff($returnedBulbs);
		}
		elseif ($returnedBulbs == null) {
			// This room has no bulbs yet, exit
			$log = $loggingStart.' Found a room with no bulbs, doing nothing '.$loggingEnd;
			logToDisk($log);
		}
	}
}
?>










