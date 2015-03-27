<?php
	include_once('../includes/header.html');
	include_once('../includes/config.php');
	include_once('../includes/db_connect.php');
	if ($mysqli->connect_error) {
  		die("Connection failed: " . $mysqli->connect_error);
	}
	
	if(isset($_GET['id'])){
		//show building-specific items
		$id = $_GET['id'];
		
		
		$sql = "SELECT buildings.short_name, buildings.full_name, classrooms.room_number, classrooms.description, classrooms.id
				FROM classrooms 
				INNER JOIN buildings 
				ON classrooms.building_id=buildings.id 
				AND buildings.id=" . $id;
		$result = $mysqli->query($sql);
		
		if($result->num_rows > 0){
			echo "<table style='width:50%'>";
			
				while($row = $result->fetch_assoc()){
					echo "<tr>";
					echo "<td>" . $row['short_name'] . "</td><td><a href=classrooms.php?id=" . $row['id'] . 
						"&full_name=" . urlencode($row['full_name']) . "&room=" . $row['room_number'] . ">" . $row['room_number'] . "</a></td>";
					if(isset($row['description'])){
						echo "<td>" . $row['description'] . "</td>";
					} else {
						echo "<td></td>";
					}
					echo "</tr>";
				}
			
			echo "</table>";
		} else {
			echo "<h4>No classrooms in this building</h4>";
		} 
	} else {
		//show building list
		echo "<h3>Buildings</h3><br>";

		$sql = "SELECT * FROM buildings ORDER BY short_name";
		$result = $mysqli->query($sql);
		if ($result->num_rows > 0) {
   			// output data of each row
   			echo "<table>";
   			echo "<tr><td><b>Building code</b></td><td><b>Building name</b></td></tr>";
   			while($row = $result->fetch_assoc()) {
   				//echo "id: " . $row["id"]. " - Name: " . $row["name"]. " Type: " . $row["type"]. "<br>";
   				//table building has rows id, full_name, short_name, address
   				echo "<tr><td>" . $row['short_name'] . "</td><td><a href=buildings.php?id=" . $row['id'] . ">" . $row['full_name'] . "</a></td></tr>";
  			}
  			echo "</table>";
		} else {
  			echo "Database failure: no buildings found";
		}
	}
	
	
	$mysqli->close();
	include_once('../includes/footer.html');
?>

