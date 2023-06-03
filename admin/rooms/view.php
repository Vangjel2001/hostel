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

    //get the information for all the rooms
    $stmt=getAllRoomsInfo($pdo);

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Rooms</title>
        <style>

        </style>
    </head>

    <body>
        <div class="container">
        <h1>Rooms</h1>
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Hostel_Dormitory.jpg">
        <table border="1">
            <tr>
                <th>Room Number</th>
                <th>Base Fee</th>
                <th>Meal Fee</th>
                <th>Capacity</th>
                <th>Students Currently In The Room</th>
                <th>Update Room</th>
                <th>Delete Room</th>
            </tr>
            <?php 
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    //display the room information
                    echo '<tr><td>';
                    echo $row['roomNumber'].'</td><td>';
                    echo $row['mealFee'].' ALL</td><td>';
                    echo $row['regularFee'].' ALL</td><td>';
                    echo $row['capacity'].'</td><td>';
                    echo $row['numberOfStudents'].'</td>';

                    //store the roomNumber
                    $roomNumber=$row['roomNumber'];

                    /*If the room has no students, allow for its 
                    editing or deletion*/
                    if((int)$row['numberOfStudents']==0)
                    {
                        echo '<td>';
                        echo '<a href="edit.php?roomNumber='
                        .$roomNumber.'">Edit</a></td><td>';
                        echo '<a href="delete.php?roomNumber='
                        .$roomNumber.'">Delete</a></td>';
                    }else
                    {
                        echo '<td></td><td></td>';
                    }
                    echo '</tr>';

                }
            
            ?>

        </table>

        <a href="add.php">Add A Room</a>
        <a href="../home.php">Go Back</a>
        <a href="../../logout.php">Log Out</a>
        </div>
    </body>
</html>