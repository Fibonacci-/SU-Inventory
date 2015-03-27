<?php
include_once('../includes/config.php');
include_once('../includes/db_connect.php');
include('../includes/header.html');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_POST["equipment_id_post"])) {
    if ($_POST["equipment_id_post"] != "") {
        $equip_id = $_POST["equipment_id_post"];
        $issue_desc = $_POST["issue_desc_post"];
        $fix_desc = $_POST["fix_desc_post"];
        $other_desc = $_POST["other_desc_post"];

        $sql = "INSERT INTO classroom_issues (equipment_id, issue, fix, other, issue_date)
                VALUES (?,?,?,?,?)";

        if($stmt = mysqli_prepare($mysqli,$sql)){
            mysqli_stmt_bind_param($stmt, "issss", $equip_id, $issue_desc, $fix_desc, $other_desc, date("Y-m-d H:i:s"));

            mysqli_stmt_execute($stmt);

            echo "Issue submitted successfully.";
        }

    }
}


echo "<form action='issue.php' method='POST'>
Equipment ID :
<input type = 'number' name = 'equipment_id_post' >
<br >
Issue Description:<br >
<textarea name = 'issue_desc_post' rows='4' cols='50'></textarea>
<br >Fix Description:<br >
<textarea name = 'fix_desc_post' rows='4' cols='50'></textarea>
<br >Other details:<br >
<textarea name = 'other_desc_post' rows='4' cols='50'></textarea>
<br>
<input type='submit'>
</form >";

include('../includes/footer.html');
?>