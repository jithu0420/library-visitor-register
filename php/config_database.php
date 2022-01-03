<?php
$servername = "localhost";
$username = "root";
$password = "";
$db="library";
// Create connection
$conn = mysqli_connect($servername, $username, $password,$db) or
    die("Could not connect to database ".mysqli_error($conn));

?>