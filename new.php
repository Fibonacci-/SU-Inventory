<?php
include_once('../includes/config.php');
include_once('../includes/db_connect.php');
include('../includes/header.html');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
echo "<h3>New...</h3>";
//manufacturer_id
//serial
//model
//original_cost
//description
//last_updated
if (isset($_POST['manufacturer_id'])) {
    //handle new equipment
    if($_POST['manufacturer_id'] != ""){
        $sql = "INSERT INTO equipment (manufacturer_id, serial, model, original_cost, description, last_updated)
                VALUES (?,?,?,?,?,?)";

        if($stmt = mysqli_prepare($mysqli,$sql)){
            mysqli_stmt_bind_param($stmt, "isssss", $_POST['manufacturer_id'], $_POST['serial'], $_POST['model'], $_POST['original_cost'],$_POST['description'], date("Y-m-d H:i:s"));

            mysqli_stmt_execute($stmt) or die();

            echo "Equipment added successfully.";
        }
    }
}

echo "<form action='new.php' method='POST'>";
//select manufacturer id
echo "<table>";
$sql = "SELECT * FROM manufacturers";
$result = $mysqli->query($sql);
echo "<tr><td>Manufacturer:</td><td> <select name='manufacturer_id'>";
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
echo "</td></tr></select>";

echo " <br><tr><td>Serial number:</td><td>    <input type='text' name = 'serial'></td></tr>
       <br><tr><td>Model number:</td><td>     <input type='text' name = 'model'></td></tr>
       <br><tr><td>Cost:</td><td>             <input type='text' name = 'original_cost'></td></tr>
       <br><tr><td>Description:</td><td>      <input type='text' name = 'description'></td></tr>
       <tr><td></td><td><input type='submit'></td></tr>
</form >";


include('../includes/footer.html');
?>