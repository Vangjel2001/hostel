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
        $_SESSION['error']="Please login first in order to continue.";
        header("Location: ../home.php");
        return;
    }

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../style.css">
        <title>Administrator Home Page</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Administrator Home Page</h1>

        <nav class="navbar">  
            <ul>

                <li>
                    <a href="profile/view.php">Manage Profile</a>
                </li>

                <li>
                    <a href="rooms/view.php">Manage Rooms</a>
                </li>

                <li>
                    <a href="students/view.php">Manage Students</a>
                </li>

                <li>
                    <a href="booking/view.php">Manage Bookings</a>
                </li>
             
            </ul>  
        </nav>

        <a href="../home.php">Go Back</a>
        <a href="../logout.php">Log Out</a>
    </body>
</html>