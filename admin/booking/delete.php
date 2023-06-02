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
    if(isset($_POST['submit']) && isset($_POST['studentID']) && 
    isset($_POST['roomNumber']))
    {
        //delete the row from the booking table
        deleteBookingRow($pdo);

        //decrease the number of students in the room by 1
        decrementNumberOfStudents($pdo);

        //Redirect to view.php with a success message
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
    $roomNumber=$_GET['roomNumber'];
    $studentID=$_GET['studentID'];

    //retreive the relevant booking-related information
    $sql="SELECT s.studentID, s.studentName, s.studentSurname, 
    s.studentEmail, b.roomNumber, b.duration, b.startDate, b.endDate, 
    b.totalFee, b.foodStatus 
    FROM student s, booking b 
    WHERE s.studentID=b.studentID AND b.roomNumber=$roomNumber 
    AND s.studentID=$studentID;";
    $stmt=$pdo->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Cancel Booking</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Cancel Booking</h1>
        <h3>Are you sure you want to cancel this booking?</h3>

        <p>Student ID: <?=$row['studentID']?></p>
        <p>Student Name: <?=$row['studentName']?></p>
        <p>Student Surname: <?=$row['studentSurname']?></p>
        <p>Student Email: <?=$row['studentEmail']?></p>
        <p></p>
        <p>Room Number: <?=$row['roomNumber']?></p>
        <p>Food Status: <?=$row['foodStatus']?></p>
        <p>Reservation Start Date: <?=$row['startDate']?></p>
        <p>Reservation End Date: <?=$row['endDate']?></p>
        <p>Reservation Duration: <?=$row['duration']?> days</p>
        <p>Total Price: <?=$row['totalFee']?> ALL</p>

        <form method="post" action="">
            <input type="hidden" name="studentID" 
            value="<?=$_GET['studentID']?>">
            <input type="hidden" name="roomNumber"
            value="<?=$_GET['roomNumber']?>">
            <input type="submit" name="submit" value="Cancel Reservation">
        </form>

        <a href="view.php">Go Back</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>