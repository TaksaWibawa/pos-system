<?php
$DBHOST = "localhost:3310";
$DBUSER = "root";
$DBPASS = "";
$DBNAME = "pbw_db";

$conn = mysqli_connect($DBHOST, $DBUSER, $DBPASS, $DBNAME);

define("SITEURL", "http://localhost:3000");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>
