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
        <div class="container">
        <h1>Below is the administrator profile information:</h1>
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Hostel_Dormitory.jpg">

        <p>Administrator Name: <?=htmlentities($row['adminName'])?></p>
        <p>Administrator Email: <?=htmlentities($row['adminEmail'])?></p>
        <p style="margin-bottom: 30px;">Administrator Phone Number: 
        <?=htmlentities($row['adminPhoneNumber'])?></p>

        <a href="edit.php">Edit Profile Information</a>
        <a href="../home.php">Go Back</a>
        <a href="../../logout.php">Log Out</a>
        </div>
    </body>
</html>