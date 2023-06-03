<?php 

    /*require the script from the database connection file
    and the file containing user-defined functions*/
    require_once "../pdo.php";
    require_once "../functions.php";

    //start the session
    session_start();

    /*display flash messages if things have gone right or wrong
    during the use of the application*/
    displayErrorMessage();
    displaySuccessMessage();

    //check if the student has logged in or signed up
    if(!isset($_SESSION['studentEmail']))
    {
        $_SESSION['error']="Please log in or sign up first in order 
        to continue.";
        header("Location: ../home.php");
        return;
    }

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../style.css">
        <title>Student Home Page</title>
        <style>

        </style>
    </head>

    <body>
        <div class="container">
        <h1>Student Home Page</h1>
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Hostel_Dormitory.jpg">

        <nav class="navbar">  
            <ul>

                <li>
                    <a href="rooms/view.php">Manage Rooms</a>
                </li>

                <li>
                    <a href="profile/view.php">Manage Profile</a>
                </li>
             
            </ul>  
        </nav>

        <a href="../prices.php">Room Prices</a>
        <a href="../home.php">Go Back</a>
        <a href="../logout.php">Log Out</a>
        </div>
    </body>
</html>