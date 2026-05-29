<?php 

$host = "localhost";
$username = "root";
$password = "";
$database = "db_try";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error){
    die("" . $conn->connect_error);
}

?>