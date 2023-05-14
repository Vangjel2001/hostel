<?php

//provide the computer, user, password and database name
$user="root";
$pw="";
$host="localhost";
$db_name="hostel";

//provide the data source name (dsn) with the computer and database name
$dsn="mysql:host=$host;dbname=$db_name";

//connect to the database and store the connection to $pdo
$pdo = new PDO($dsn, $user, $pw);

//throw exceptions when things go wrong when accessing and using the
//database (useful when developing the application, not after its launch)
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
