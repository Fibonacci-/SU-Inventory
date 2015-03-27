<?php
	include_once('../includes/config.php');
	include_once('../includes/db_connect.php');
	include('../includes/header.html');
	if ($mysqli->connect_error) {
  		die("Connection failed: " . $mysqli->connect_error);
	}
	
	
	if(isset($_GET['id'])){
		$name = $_GET['full_name'];
		$room = $_GET['room'];
		$class_id = $_GET['id'];
		echo "<h3>" . $name . " room " . $room . "</h3><br>";
		//list all items in classroom by manufacturer, then model name
		//order: id# of equipment, manufacturer, model, description, serial
		$sql = "SELECT equipment.id, manufacturers.name, equipment.model, equipment.description, equipment.serial 
		FROM equipment 
		INNER JOIN manufacturers ON equipment.manufacturer_id=manufacturers.id
		WHERE equipment.classroom_id=$class_id 
		ORDER BY manufacturers.name DESC, model DESC";
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			echo "<table style='width:50%'>";
			echo "<tr><td><b>ID</b></td><td><b>Manufacturer</b></td><td><b>Model</b></td><td><b>Description</b></td><td><b>Serial</b></td><td></td></tr>";
			while ($row = $result->fetch_assoc()){
				$equipment_link = "<a href=equipment.php?id=" . $row['id'] . ">" . $row['id'] . "</a>";
				echo "<tr>";
				
				echo "<td>" . $equipment_link . "</td><td>" . $row['name'] . "</td><td>" . $row['model'] . "</td><td>" . 
					$row['description'] . "</td><td>" . $row['serial'] . "</td><td><a href='assign.php?deassign_equipment_id=" . $row['id'] . "'>Mark as spare</a></td>";
				
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "<h3>There was no equipment found in this room.</h3>";
		}

		echo "<h3>History of issues with this classroom</h3>";
		$sql = "SELECT classroom_issues.id, classroom_issues.classroom_id, classroom_issues.equipment_id,
					classroom_issues.issue, classroom_issues.fix, classroom_issues.other, classroom_issues.issue_date,
					equipment.id AS equipment_prim_key, equipment.model, manufacturers.name, equipment.serial
				FROM classroom_issues
				INNER JOIN equipment ON classroom_issues.equipment_id=equipment.id
				INNER JOIN manufacturers ON manufacturers.id=equipment.manufacturer_id
				WHERE classroom_issues.classroom_id=$class_id";
		$result = $mysqli->query($sql);
		if($result->num_rows>0){
			echo "<table class=equip_table>";
			echo "<tr><td><b>Manufacturer</b></td><td><b>Model</b></td><td><b>Serial</b></td><td><b>Issue</b></td><td><b>Fix</b></td><td><b>Notes</b></td><td><b>Date</b></td></tr>";
				while($row=$result->fetch_assoc()){
					echo "<tr><td>" . $row['name'] . "</td><td>" . $row['model'] . "</td><td>" . $row['serial'] . "</td><td>" . $row['issue'] . "</td><td>" . $row['fix'] . "</td><td>" . $row['other'] . "</td><td>" . $row['issue_date'] . "</td></tr>";
				}

			echo "</table>";
		} else {
			echo "<h5>No issues logged for this equipment</h5>";
		}
	
	} else {
		echo "<h3>Classrooms</h3><br>";

		$sql = "SELECT buildings.short_name, buildings.full_name, classrooms.room_number, classrooms.description, classrooms.id
		FROM classrooms 
		INNER JOIN buildings 
		ON classrooms.building_id=buildings.id
		ORDER BY buildings.full_name ASC, classrooms.room_number ASC";
		$result = $mysqli->query($sql);
		if ($result->num_rows > 0) {
   			// output data of each row
   			echo "<table>";
   			echo "<tr><td><b>Building code</b></td><td><b>Building name</b></td><td><b>Room</b></td><td><b>Notes</b></td></tr>";
   			while($row = $result->fetch_assoc()) {
   				//echo "id: " . $row["id"]. " - Name: " . $row["name"]. " Type: " . $row["type"]. "<br>";
   				//table building has rows id, full_name, short_name, address
   				echo "<tr>";
   				echo "<td>" . $row['short_name'] . "</td><td>" . $row['full_name'] . "</td><td>" . 
   					"<a href=classrooms.php?id=" . $row['id'] . "&full_name=" . urlencode($row['full_name']) . "&room=" . $row['room_number'] . ">" . $row['room_number'] . "</a>" . "</td><td>" . $row['description'] . "</td>";
   				echo "</tr>";
  			}
  			echo "</table>";
		} else {
  			echo "Database failure: no buildings found";
		}
	}
	
	
include('../includes/footer.html');
?>
