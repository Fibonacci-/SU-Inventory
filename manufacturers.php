<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/7/2015
 * Time: 04:13 PM
 */
include_once('../includes/config.php');
include_once('../includes/db_connect.php');
include('../includes/header.html');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


if(isset($_POST['name'])){
    //handle new manufacturer
    $sql = "INSERT INTO manufacturers (name,type)
                VALUES (?,?)";

    if($stmt = mysqli_prepare($mysqli,$sql)){
        mysqli_stmt_bind_param($stmt, "ss", $_POST['name'], $_POST['type']);

        mysqli_stmt_execute($stmt) or die();

        echo "Manufacturer added successfully.";
    }
}

echo "<form action='manufacturers.php' method='POST'>
Manufacturer name: <input type='text' name='name'><br>
Manufacturer type: <input type='text' name='type'><br>
<input type='submit'>
</form >";

include('../includes/footer.html');