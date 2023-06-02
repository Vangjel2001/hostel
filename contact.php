<?php 

    /*require the script from the database connection file
    and the file containing user-defined functions*/
    require_once "C:/xampp\htdocs\hostel\pdo.php";
    require_once "C:/xampp\htdocs\hostel/functions.php";

    //start the session
    session_start();

    //get the administrator email and phone number
    $sql="SELECT adminEmail, adminPhoneNumber 
    FROM administrator;";

    $stmt=$pdo->query($sql);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);

    $adminEmail=htmlentities($row['adminEmail']);
    $adminPhoneNumber=htmlentities($row['adminPhoneNumber']);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Contact Us</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <h1>Contact Us</h1>

        <p>Below are the phone number and email adress of the 
            hostel administrator:
        </p>
        <p></p>

        <p>Phone Number: <?=$adminPhoneNumber?></p>
        <p>Email: <?=$adminEmail?></p>
        <p></p>
        <a href="#" onclick="history.go(-1); return false;">Go Back</a>
    </body>

</html>