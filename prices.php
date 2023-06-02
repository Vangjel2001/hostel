<?php 

    /*require the script from the database connection file
    and the file containing user-defined functions*/
    require_once "C:/xampp\htdocs\hostel\pdo.php";
    require_once "C:/xampp\htdocs\hostel/functions.php";

    //start the session
    session_start();

    //get the information of every room
    $stmt=getAllRoomsInfo($pdo);

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Reservation Prices</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <h1>Reservation Prices</h1>

        <p>Each room has a daily stay fee that you have to pay each 
            day even if you do not want any meals served by our hostel 
            during your stay. If you want let's say 2 meals served per 
            day, you have to pay 2 times the meal fee displayed below 
            every day on top of the daily fee.
        </p>
        <table border="1">
            <tr>
                <th>Room Number</th>
                <th>Daily Fee</th>
                <th>Meal Fee</th>
            </tr>

            <?php 
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    /*If the room has no students, display its 
                    information*/
                    if((int)$row['numberOfStudents']==0)
                    {
                        //display the room information
                    echo '<tr><td>';
                    echo $row['roomNumber'].'</td><td>';
                    echo $row['regularFee'].' ALL</td><td>';
                    echo $row['mealFee'].' ALL</td></tr>';
                    }
                }
            ?>

        </table>

        <a href="#" onclick="history.go(-1); return false;">Go Back</a>
        <a href="logout.php">Log Out</a>
    </body>
</html>