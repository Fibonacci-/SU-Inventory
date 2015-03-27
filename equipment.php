<?php
include_once('../includes/config.php');
include_once('../includes/db_connect.php');
include('../includes/header.html');
	if ($mysqli->connect_error) {
  		die("Connection failed: " . $mysqli->connect_error);
	}

/*
So:
This file has several purposes
When it is loaded directly, it will show a list off all equipment on campus, ordered by building ASC, then manufacturer ASC, then model ASC
	This view will show building short, equipment id, equipment manufacturer, equipment model
When an ID is passed, it will show details for that piece of equipment
	including building short/long, equipment id, manufacturer, model, serial, 
		then a history of issues with that piece of equipment
*/
	if(isset($_GET['id'])){
		//will need:
		//equip.id, manufacturer name, model, serial, date put in classroom, original cost
		//description and date installed
		//table of all issues related to this piece of equipment
		$id = $_GET['id'];
		$sql = "SELECT equipment.id, manufacturers.name, equipment.serial, equipment.model, equipment.last_updated,
					equipment.date_installed, equipment.original_cost, equipment.classroom_id, buildings.full_name, 
					classrooms.room_number, buildings.id AS building_id
				FROM equipment
				LEFT OUTER JOIN manufacturers ON manufacturers.id=equipment.manufacturer_id
				LEFT OUTER JOIN classrooms ON classrooms.id=equipment.classroom_id
				LEFT OUTER JOIN buildings ON buildings.id=classrooms.building_id
				WHERE equipment.id=$id";

		$result = $mysqli->query($sql);
		$row;
		if($result->num_rows>0){
			$row=$result->fetch_assoc();//there can only be one
			if(!is_null($row['room_number'])){
				echo "<h3>" . $row['model'] . " in " . $row['full_name'] . " " . $row['room_number'] . "</h3>";
			} else {
				echo "<h3>" . $row['model'] . " is a spare (id " . $row['id'] . ")</h3>";
			}
			echo "<table>";

				echo "<tr><td><b>ID</b></td><td>" . $row['id'] . "</td></tr>";
				echo "<tr><td><b>Manufacturer</b></td><td>" . $row['name'] . "</td></tr>";
				echo "<tr><td><b>Model</b></td><td>" . $row['model'] . "</td></tr>";

				if(!is_null($row['room_number'])){
					$classroom_link = "<a href=classrooms.php?id=" . $row['classroom_id'] . 
						"&full_name=" . urlencode($row['full_name']) . 
						"&room=" . $row['room_number'] . ">" . $row['room_number'] . "</a>";
					$building_link = "<a href=buildings.php?id=" . $row['building_id'] . ">" . 
						$row['full_name'] . "</a>";
					echo "<tr><td><b>Location</b></td><td>" . $building_link . " room " . $classroom_link . "</td></tr>";
				} else {
					echo "<tr><td><b>Location</b></td><td>Unassigned</td></tr>";
				}

				echo "<tr><td><b>Serial</b></td><td>" . $row['serial'] . "</td></tr>";
				echo "<tr><td><b>Purchase cost</b></td><td>" . $row['original_cost'] . "</td></tr>";
				echo "<tr><td><b>Date installed</b></td><td>" . $row['date_installed'] . "</td></tr>";
				echo "<tr><td><b>Date updated</b></td><td>" . $row['last_updated'] . "</td></tr>";

			echo "</table>";
		} else {
			echo "<h3>Invalid ID</h3>";
		}

		echo "<h3>History of issues with this equipment</h3>";
		$sql = "SELECT * FROM classroom_issues
				INNER JOIN equipment ON classroom_issues.equipment_id=equipment.id
				WHERE classroom_issues.equipment_id=$id";
		$result = $mysqli->query($sql);
		if($result->num_rows>0){
			echo "<table class=equip_table>";
			echo "<tr><td><b>Issue</b></td><td><b>Fix</b></td><td><b>Notes</b></td><td><b>Date</b></td></tr>";
				while($row=$result->fetch_assoc()){
					echo "<tr><td>" . $row['issue'] . "</td><td>" . $row['fix'] . "</td><td>" . $row['other'] . "</td><td>" . $row['issue_date'] . "</td></tr>";
				}

			echo "</table>";
		} else {
			echo "<h5>No issues logged for this equipment</h5>";
		}

	} else {

		echo "<h3>Equipment owned</h3><br>";

		$sql = "SELECT buildings.short_name, buildings.full_name, equipment.id, manufacturers.name, 
				equipment.model, buildings.id AS building_id, classrooms.room_number, classrooms.id AS classroom_id
			FROM equipment
			LEFT OUTER JOIN classrooms ON equipment.classroom_id = classrooms.id
			LEFT OUTER JOIN buildings ON classrooms.building_id = buildings.id
			LEFT OUTER JOIN manufacturers ON equipment.manufacturer_id = manufacturers.id
			ORDER BY buildings.short_name DESC , manufacturers.name ASC , equipment.model ASC ";
		$result = $mysqli->query($sql);
		
		if($result->num_rows>0){
			echo "<table style='width:50%'>";
			echo "<tr><td><b>Building</b></td><td><b>Classroom</b></td><td><b>Equipment ID</b></td><td><b>Manufacturer</b></td><td><b>Model</b></td></tr>";
			while($row=$result->fetch_assoc()){
				$building_link = "<a href=buildings.php?id=" . $row['building_id'] . ">" . $row['short_name'] . "</a>";
				$classroom_link = "<a href=classrooms.php?id=" . $row['classroom_id'] . "&full_name=" . urlencode($row['full_name']) . "&room=" . $row['room_number'] . ">" . $row['room_number'] . "</a>";
				$equipment_link = "<a href=equipment.php?id=" . $row['id'] . ">" . $row['id'] . "</a>";
				if(is_null($row['short_name'])){
					$building_link = "Unassigned";
				}
				echo "<tr><td>" . $building_link . "</td><td>" . $classroom_link . "</td><td>" . $equipment_link . "</td><td>" . $row['name'] . "</td><td>" . $row['model'] . "</td></tr>";
			}
			echo "</table>";
		} else {
			echo "<h3>Database communication error or no equipment added</h3>";
		}
	}

include('../includes/footer.html');
?>
