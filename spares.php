<?php
include_once('../includes/config.php');
include_once('../includes/db_connect.php');
include('../includes/header.html');
	if ($mysqli->connect_error) {
		die("Connection failed: " . $mysqli->connect_error);
	}

	echo "<h3>Spare equipment</h3><br>";

	$sql = "SELECT equipment.id, manufacturers.name, equipment.model, equipment.description
		FROM equipment
		INNER JOIN manufacturers ON equipment.manufacturer_id=manufacturers.id
		WHERE equipment.classroom_id IS NULL
		ORDER BY manufacturers.name ASC, equipment.model DESC";
	$result = $mysqli->query($sql);

	if($result->num_rows>0){
		echo "<table>";
		echo "<tr><td><b>ID</b></td><td><b>Manufacturer</b></td><td><b>Model</b></td><td><b>Notes</b></td><td></td></tr>";
		
		while($row=$result->fetch_assoc()){
			$equip_link = "<a href=equipment.php?id=" . $row['id'] . ">" . $row['id'] . "</a>";
			echo "<tr><td>" . $equip_link . "</td><td>" . $row['name'] . "</td><td>" . $row['model'] . "</td><td>" . $row['description'] . "</td><td>
				<a href='assign.php?equip_id=" . $row['id'] . "'>Assign to room</a></td></tr>";
		}
		echo "</table>";

	} else {
		echo "<h3>No spares. Not even one :(</h3>";
	}


include('../includes/footer.html');
?>
