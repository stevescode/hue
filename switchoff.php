<?php

/**
 * @author stevescode
 * @link http://www.phpdoc.org/docs/latest/index.html
 */

include 'vars.php';

function checkBulb($theBulb) {
	global $hueApi, $hueIp;

	$url = "http://".$hueIp."/api/".$hueApi."/lights/".$theBulb."/";
	$json = file_get_contents($url);
	$data = json_decode($json);

	if ($data->state->on == 1) {
		return 1;
	}
	else {
		return 0;
	}
}

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

// This is hacky - fix it

echo 'Action is starting... </br>';

$bulbState = 0;

if (checkBulb(8) == 1 || checkBulb(9) == 1) {
	$bulbState = $bulbState +1;
	echo 'At least one bulb is on </br>';

	if (checkMovement('lounge', 900) == 1) {
	echo 'There is movement showing in the database, doing nothing </br>';
	// do nothing
	}
	else {
		echo '</br>no movement in the room, turning off bulb 8...</br>';
		switchOff(8);
		echo '</br>no movement in the room, turning off bulb 9...</br>';
		switchOff(9);
	}	
}
else {
	echo 'bulbs are already off';
}

?>
