<?php
// Database connection settings for My SiteGround
$servername = "127.0.0.1"; 
$username = "uahqdqmnp2gba";
$password = "FabriceMukarage23@";
$dbname = "dblbjpkntadz0b";

// Create connection
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
