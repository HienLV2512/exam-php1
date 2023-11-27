<?php
require './includes/db_connect.php';
$id = $_GET['flight_id'];
$sql = "DELETE FROM flights WHERE flight_id = '$id'";
$conn->exec($sql);
header("location: index.php");
