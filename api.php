<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/28/2015
 * Time: 06:31 PM
 */
include_once('../includes/config.php');
include_once('../includes/db_connect.php');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$request = $_GET['request_type'];

if($request = "list_buildings"){
    //get list of buildings
    $sql = "SELECT id, full_name, short_name FROM buildings";
    $result = $mysqli->query($sql);

    $json = array();

    while ($row = $result->fetch_assoc()){
        array_push($json, $row);
    }

    echo json_encode($json);

}