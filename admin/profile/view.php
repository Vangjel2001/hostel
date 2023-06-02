<?php 
    /*require the script from the database connection file
    and the file containing user-defined functions*/
    require_once "C:/xampp\htdocs\hostel\pdo.php";
    require_once "C:/xampp\htdocs\hostel/functions.php";

    //start the session
    session_start();

    /*display flash messages if things have gone right or wrong
    during the use of the application*/
    displayErrorMessage();
    displaySuccessMessage();

    //check if the administrator has logged in
    if(!isset($_SESSION['adminName']))
    {
        $_SESSION['error']="Please log in first in order 
        to continue.";
        header("Location: ../../home.php");
        return;
    }

    //receive the information regarding the administrator
    $row=getAdministratorRow($pdo);

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Administrator Profile</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Below is the administrator profile information:</h1>

        <p>Administrator Name: <?=htmlentities($row['adminName'])?></p>
        <p>Administrator Email: <?=htmlentities($row['adminEmail'])?></p>
        <p>Administrator Phone Number: 
        <?=htmlentities($row['adminPhoneNumber'])?></p>

        <a href="edit.php">Edit profile information</a>
        <a href="../home.php">Go Back</a>
        <a href="../../logout.php">Log Out</a>
        
    </body>
</html>