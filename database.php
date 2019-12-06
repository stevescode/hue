<?php

/**
 * @author stevescode
 * @link http://www.phpdoc.org/docs/latest/index.html
 */

include 'vars.php';

$table = $_GET['table'];

if ($table == 'activity') {
	$sensor = $_GET['sensor'];
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "INSERT INTO activity (sensor, timestamp) VALUES ('".$sensor."', '".$date."')";

	if (mysqli_query($conn, $sql)) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
}

elseif ($table == 'status') {
	$area = $_GET['area'];
	$status = $_GET['status'];

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "INSERT INTO status (area, status, timestamp) VALUES ('".$area."', '".$status."', '".$date."')";

	if (mysqli_query($conn, $sql)) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);	
}

?>
