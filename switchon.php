<?php

/**
 * @author stevescode
 * @link http://www.phpdoc.org/docs/latest/index.html
 * This script just sets the Lounge to correct evening settings - this needs expanding.
 */

include 'vars.php';

$action = (string)$_GET['action'];

function activeLights($light) {
	global $hueApi;
	global $hueIp;

	if ($light == 'overhead') {
			$arrData['on'] = true;
			$arrData['bri'] = 54;
			$arrData['hue'] = 59427;
			$arrData['sat'] = 49;
			$arrData['effect'] = none;
			$arrData['xy'] = [0.4572,0.4099];
			$arrData['ct'] = 366;
			$bulb = 8;
	}

	elseif ($light == 'behindTV') {
			$arrData['on'] = true;
			$arrData['bri'] = 254;
			$arrData['hue'] = 52424;
			$arrData['sat'] = 111;
			$arrData['effect'] = none;
			$arrData['xy'] = [0.3490,0.2658];
			$arrData['ct'] = 203;
			$bulb = 9;
	}

	elseif ($light == 'downstairshall1') {
			$arrData['on'] = true;
			$arrData['bri'] = 254;
			$arrData['hue'] = 14988;
			$arrData['sat'] = 141;
			$arrData['effect'] = none;
			$arrData['xy'] = [0.4575,0.4101];
			$arrData['ct'] = 366;
			$bulb = 1;
	}

	elseif ($light == 'downstairshall2') {
			$arrData['on'] = true;
			$arrData['bri'] = 254;
			$arrData['hue'] = 14988;
			$arrData['sat'] = 141;
			$arrData['effect'] = none;
			$arrData['xy'] = [0.4575,0.4101];
			$arrData['ct'] = 366;
			$bulb = 4;
	}

	$data = json_encode($arrData);

	$url = "http://".$hueIp."/api/".$hueApi."/lights/".$bulb."/state";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

	$response = curl_exec($ch);
	echo $response; 

exit;
}

if($action) {
  
activeLights($action);

exit;

}

else {
	echo "no post data";
}

?>
