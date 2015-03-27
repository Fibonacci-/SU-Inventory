<?php
include_once('../includes/config.php');
include_once('../includes/db_connect.php');
include('../includes/header.html');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}



echo "<h4>Show me equipment with...</h4>";

echo "<form action='filter.php' id='filter_form' method='POST'>";

echo "<table>";
echo "<tr><td>Manufacturer</td><td>Building</td></tr>";
echo "<tr><td><select name='manufacturers' form='filter_form'>";
echo "<option></option>";
$sql = "SELECT name FROM manufacturers";
$result = $mysqli->query($sql);
while($row=$result->fetch_assoc()){
    echo '<option>'.$row['name'].'</option>';
}

echo "</select></td><td><select name='buildings' form='filter_form'>";
echo "<option></option>";
$sql = "SELECT short_name FROM buildings";
$result = $mysqli->query($sql);
while($row=$result->fetch_assoc()){
    echo '<option>'.$row['short_name'].'</option>';
}

echo "</select></td></tr>";
echo "</table>";
echo "<input type='submit' value='Go'></form>";

echo "<br>";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //echo 'Post detected';
    $sql = "SELECT equipment.id, manufacturers.name, equipment.model, buildings.short_name, classrooms.room_number, equipment.serial
					FROM equipment
					LEFT OUTER JOIN manufacturers ON equipment.manufacturer_id=manufacturers.id
					LEFT OUTER JOIN classrooms ON equipment.classroom_id=classrooms.id
					LEFT OUTER JOIN buildings ON classrooms.building_id=buildings.id";
    $man =  $_POST['manufacturers'];
    $build = $_POST['buildings'];
    $man_set = false;
    if($man != ""){
        $sql = $sql . " WHERE manufacturers.name='$man'";
        $man_set = true;
    }
    if($build != "" && $man_set){
        $sql = $sql . " AND buildings.short_name='$build'";
    }
    if($build != "" && !$man_set){
        $sql = $sql . " WHERE buildings.short_name='$build'";
    }

    $result = $mysqli->query($sql);

    if($result){
        echo "<h4>Found " . $result->num_rows . " items</h4>";
        echo "<table>";
        $new_header_row = "<tr><td><b>";
        $new_header = "</b></td><td><b>";
        $end_header_row = "</b></td></tr>";

        $new_row = "<tr><td>";
        $new_line = "</td><td>";
        $end_row = "</td></tr>";

        echo $new_header_row . "Equipment ID" . $new_header . "Manufacturer" . $new_header . "Model" . $new_header .
            "Building" . $new_header . "Room" . $new_header . "Serial" . $end_header_row;
        while($row=$result->fetch_assoc()){
            echo $new_row . $row['id'] . $new_line . $row['name'] . $new_line . $row['model'] . $new_line . $row['short_name'] . $new_line . $row['room_number'] . $new_line . $row['serial'] . $end_row;
        }
        echo "</table>";
    } else {
        echo "<h3>No equipment found. Please widen your query.";
        echo $sql;

    }


}

include('../includes/footer.html');
?>