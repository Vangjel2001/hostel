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

    //check if the student has logged in or signed up
    if(!isset($_SESSION['studentEmail']))
    {
        $_SESSION['error']="Please log in or sign up first in order 
        to continue.";
        header("Location: ../../home.php");
        return;
    }

    //check if the form has been submitted
    if(isset($_POST['submit']) && isset($_POST['studentID']) && 
    isset($_POST['roomNumber']))
    {
        //delete the row from the booking table
        deleteBookingRow($pdo);

        //decrease the number of students in the room by 1
        decrementNumberOfStudents($pdo);

        $_SESSION['success']="Your booking was successfully cancelled!";
        
        header("Location: view.php");
        return;
    }

    //check if the user has typed delete.php as a url
    if(missingGetParameters("delete.php"))
    {
        header("Location: view.php");
        return;
    }

    /*check if the user has typed delete.php as a url with inappropriate 
    get parameters following it*/
    if(wrongGetParameters("delete.php", $pdo))
    {
        header("Location: view.php");
        return;
    }

    //get the details of the reservation
    $row=getBookingInfo($pdo);

    //store the reservation details in variables with shorter names
    $roomNumber=$row['roomNumber'];
    $foodStatus=$row['foodStatus'];
    $duration=$row['duration'];
    $totalFee=$row['totalFee'];

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Reservation Cancelling</title>
        <style>

        </style>
    </head>

    <body>
        <div class="container">
        <h1>Cancelling Room Reservation</h1>
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Hostel_Dormitory.jpg">
        <h3>Are you sure you want to cancel your reservation of the 
            following room?</h3>

        <p>Room Number: <?=$roomNumber?></p>
        <p>Food Status: <?=$foodStatus?></p>
        <p>Reservation Duration: <?=$duration?> days</p>
        <p>Total Price: <?=$totalFee?> ALL</p>

        <form method="post" action="">
            <input type="hidden" name="studentID" 
            value="<?=$_GET['studentID']?>">
            <input type="hidden" name="roomNumber"
            value="<?=$_GET['roomNumber']?>">
            <input type="submit" name="submit" value="Cancel Reservation">
        </form>

        <a href="../../prices.php">Room Prices</a>
        <a href="view.php">Go Back</a>
        <a href="../../logout.php">Log Out</a>
        </div>
    </body>
</html>