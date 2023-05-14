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
        header("Location: C:/xampp\htdocs\hostel\home.php");
        return;
    }
    
    //get the details of each of the student's reservations and rooms
    $stmt=getBookingsAndRoomsInfo($_SESSION['studentID'], $pdo);


?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" 
        href="file:///C:/xampp/htdocs/hostel/style.css">
        <title>Student Rooms</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Student Rooms</h1>
        <h3>Below is your room information:</h3>

        <h4>Rooms:</h4>
        <table border="1">
            <tr>
                <th>Room Number</th>
                <th>Food Status</th>
                <th>Number of inhabitants</th>
                <th>Room Capacity</th>
                <th>Reservation Duration</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Price</th>
                <th>Edit Reservation</th>
                <th>Cancel Reservation</th>
            </tr>
            
            <?php
                $studentID=$_SESSION['studentID'];

                //check if the student has not booked any rooms yet
                if($stmt->rowCount()==0)
                {
                    echo '<p>You have not booked any rooms yet.</p>';
                }
                else
                { 
                    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        //display information
                        echo '<tr><td>';
                        echo $row['roomNumber'].'</td><td>';
                        echo $row['foodStatus'].'</td><td>';
                        echo $row['numberOfStudents'].'</td><td>';
                        echo $row['capacity'].'</td><td>';
                        echo $row['duration'].' days</td><td>';
                        echo $row['startDate'].'</td><td>';
                        echo $row['endDate'].'</td><td>';
                        echo $row['totalFee'].' ALL</td><td>';
                        
                        $roomNumber=$row['roomNumber'];

                        //links to edit.php and delete.php
                        echo '<a href="edit.php?studentID='.$studentID 
                        .'&roomNumber='.$roomNumber.'">Edit</a>
                        </td><td>';
                        echo '<a href="delete.php?studentID='.$studentID
                        .'&roomNumber='.$roomNumber.'">Delete</a>
                        </td><td>';
                    }
                }
            ?>

        </table>

        <p></p>
        <p></p>
        <a href="add.php">Book a room</a>

        <a href="../home.php">Go Back</a>
    </body>
</html>