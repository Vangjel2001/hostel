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

    //retreive the relevant booking-related information
    $sql="SELECT s.studentID, s.studentName, s.studentSurname, 
    s.studentEmail, 
    b.roomNumber, b.duration, b.startDate, b.endDate, b.totalFee, 
    b.foodStatus, r.capacity, r.numberOfStudents 
    FROM student s, booking b, room r 
    WHERE s.studentID=b.studentID AND b.roomNumber=r.roomNumber 
    ORDER BY s.studentID, b.roomNumber;";
    $stmt=$pdo->query($sql);

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Bookings</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Bookings</h1>
        <table border="1">
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Student Surname</th>
                <th>Room Number</th>
                <th>Reservation Duration</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Price</th>
                <th>Room Food Status</th>
                <th>Capacity</th>
                <th>Students Currently In The Room</th>
                <th>Edit Reservation</th>
                <th>Cancel Reservation</th>
            </tr>

            <?php 
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    //display the booking-related information
                    echo '<tr><td>';
                    echo $row['studentID'].'</td><td>';
                    echo $row['studentName'].'</td><td>';
                    echo $row['studentSurname'].'</td><td>';
                    echo $row['roomNumber'].'</td><td>';
                    echo $row['duration'].' days</td><td>';
                    echo $row['startDate'].'</td><td>';
                    echo $row['endDate'].'</td><td>';
                    echo $row['totalFee'].' ALL</td><td>';
                    echo $row['foodStatus'].'</td><td>';
                    echo $row['capacity'].' people</td><td>';
                    echo $row['numberOfStudents'].'</td><td>';

                    //store the roomNumber
                    $roomNumber=$row['roomNumber'];
                    //store the studentID
                    $row1=returnStudentRow($row['studentEmail'], $pdo);
                    $studentID=$row1['studentID'];

                        //links to edit.php and delete.php
                        echo '<a href="edit.php?studentID='.$studentID 
                        .'&roomNumber='.$roomNumber.'">Edit</a>
                        </td><td>';
                        echo '<a href="delete.php?studentID='.$studentID
                        .'&roomNumber='.$roomNumber.'">Delete</a>
                        </td></tr>';
                }
            ?>
        </table>

        <a href="add.php">Add A Reservation</a>
        <a href="../home.php">Go Back</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>