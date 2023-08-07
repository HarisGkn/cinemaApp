<?php
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'cinemadb');
 
/* Attempt to connect to MySQL database */
// αποπειρα συνδεσης στη βαση
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
// ελεγχος συνδεσης
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>