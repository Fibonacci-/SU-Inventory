<?php
	include_once('../includes/header.html');
	//# buildings
	//# classrooms
	//# pieces of equipment total
	//# pieces of equipment deployed
	include_once('../includes/config.php');
	include_once('../includes/db_connect.php');
	if ($mysqli->connect_error) {
  		die("Connection failed: " . $mysqli->connect_error);
	}
	
	$sql = "SELECT * FROM buildings";
	$result = $mysqli->query($sql);
	$num_buildings = mysqli_num_rows($result);
	echo "<h4>" . $num_buildings . " buildings on campus</h4>";
	
	$sql = "SELECT * FROM classrooms";
	$result = $mysqli->query($sql);
	$num_classrooms = mysqli_num_rows($result);
	echo "<h4>" . $num_classrooms . " classrooms on campus</h4>";
	
	$sql = "SELECT * FROM equipment";
	$result = $mysqli->query($sql);
	$num_classrooms = mysqli_num_rows($result);
	echo "<h4>" . $num_classrooms . " pieces of equipment owned</h4>";
	
	echo "<ul>";

$sql = "SELECT * FROM equipment WHERE classroom_id IS NOT NULL";
$result = $mysqli->query($sql);
$num_deployed_equipment = mysqli_num_rows($result);
echo "<li>" . $num_deployed_equipment . " pieces of equipment deployed</li>";

$sql = "SELECT * FROM equipment WHERE classroom_id IS NULL";
$result = $mysqli->query($sql);
$num_not_deployed_equipment = mysqli_num_rows($result);
echo "<li>" . $num_not_deployed_equipment . " spare pieces of equipment</li>";

echo "</ul>";
	$mysqli->close();
include('../includes/footer.html');
?>
