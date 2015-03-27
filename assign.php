<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/7/2015
 * Time: 04:23 PM
 */
include_once('../includes/config.php');
include_once('../includes/db_connect.php');
include('../includes/header.html');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if(isset($_GET['deassign_equipment_id'])){
    $date = date("Y-m-d H:i:s");
    $sql = 'UPDATE equipment
    SET equipment.classroom_id=NULL, equipment.last_updated="' . $date . '"
    WHERE equipment.id=' . $_GET['deassign_equipment_id'];
    //echo $sql;
    $result = $mysqli->query($sql);

    header("Location: spares.php");
    exit;
}

$equip_id = $_GET['equip_id'];


if(isset($_GET['classroom_id'])){
    $date = date("Y-m-d H:i:s");
    $sql = 'UPDATE equipment
    SET equipment.classroom_id=' . $_GET['classroom_id'] . ', equipment.last_updated="' . $date . '", equipment.date_installed="' . $date . '"
    WHERE equipment.id=' . $equip_id;
    //echo $sql;
    $result = $mysqli->query($sql);
    header("Location: classrooms.php");

}


echo "<form action='assign.php' method='GET'>Equipment ID:
    <input type='text' name='equip_id' value=" . $equip_id . ">
    <br>
    Classroom ID:
    <select name='classroom_id'>";

$sql = "SELECT classrooms.id AS class_id, full_name, room_number FROM classrooms
        INNER JOIN buildings ON classrooms.building_id=buildings.id";

$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['class_id'] . '">' . $row['full_name'] . " " . $row['room_number'] . '</option>';
}
echo '</select><br><br><input type="submit">';
echo '</form>';



include('../includes/footer.html');