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

    //check if the form has been submitted
    if(isset($_POST['submit']))
    {
        //delete the room from the room table
        deleteRoom($pdo);
        
        //redirect to view.php with a success message
        $_SESSION['success']="The room was successfully deleted!";
        header("Location: view.php");
        return;
    }

    //get the room information
    $row=getRoomInfo($_GET['roomNumber'], $pdo);

    //store the room information
    $mealFee=$row['mealFee'];
    $roomNumber=$row['roomNumber'];
    $regularFee=$row['regularFee'];
    $capacity=$row['capacity'];
    $numberOfStudents=$row['numberOfStudents'];

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Delete Room</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Delete Room</h1>
        <h3>Are you sure you want to delete the room with the following 
            information?</h3>

        <p>Room Number: <?=$roomNumber?></p>
        <p>Regular Fee: <?=$regularFee?> ALL</p>
        <p>Meal Fee: <?=$mealFee?> ALL</p>
        <p>Number Of Students: <?=$numberOfStudents?></p>
        <p>Capacity: <?=$capacity?> people</p>

        <form method="post" action="">
            <input type="hidden" name="roomNumber"
            value="<?=$roomNumber?>">
            <input type="submit" name="submit" value="Delete Room">
        </form>

        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>