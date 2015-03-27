<?php
	
	//this is where login checking will go
/*
*Create layout
*Make filters by
**All equipment
***literally just list all equipment on campus, with locations, times, etc
**All classrooms
***List all classrooms on campus, ordered alphabetically first by building then by order of room #
***Make each classroom a link to an inside view of the classroom
****List all equipment in the classroom
****List history of changes in the classroom
****List history of issues in the classroom
**All buildings
***Each building is a link to a list of all the classrooms contained
*Make display pages for
**Equipment list
**Classroom list
**Building list
**Classroom view
*/
	if(true){
		include('../includes/header.html');
		include_once('../includes/db_connect.php');
		include_once('../includes/config.php');
		//$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
		if ($mysqli->connect_error) {
    			die("Connection failed: " . $mysqli->connect_error);
		} 
		
		$sql = "SELECT * FROM manufacturers";
		$result = $mysqli->query($sql);
		if ($result->num_rows > 0) {
   		 // output data of each row
   		 while($row = $result->fetch_assoc()) {
   		     echo "id: " . $row["id"]. " - Name: " . $row["name"]. " Type: " . $row["type"]. "<br>";
  		  }
		} else {
  		  echo "0 results";
		}
		

		
		
		include('../includes/footer.html');

	} else {
		echo "You are not logged in!";
	}

?>
