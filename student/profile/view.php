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

    //store the studentID in a variable with a short name
    $studentID=$_SESSION['studentID'];

    //receive the information regarding the student and his/her guardian
    $row=getStudentAndGuardianRow($studentID, $pdo);


?>



<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Student Profile View</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Student Profile</h1>

        <p>Name: <?php echo $row['studentName'] ?></p>
        <p>Surname: <?php echo $row['studentSurname'] ?></p>
        <p>Email: <?php echo $row['studentEmail'] ?></p>
        <p>Phone Number: <?php echo $row['studentPhoneNumber'] ?></p>
        <p>Gender: <?php echo $row['studentGender'] ?></p>
        <p>Age: <?php echo $row['studentAge'] ?></p>
        <p>Education: <?php echo $row['education'] ?></p>
        <p>Address: <?php echo $row['studentAddress'] ?></p>
        <p></p>

        <p>Guardian Name: <?php echo $row['guardianName'] ?></p>
        <p>Guardian Surname: <?php echo $row['guardianSurname'] ?></p>
        <p>Guardian Age: <?php echo $row['guardianAge'] ?></p>
        <p>Guardian Phone Number: 
            <?php echo $row['guardianPhoneNumber'] ?></p>
        <p>Guardian Email Address: <?php echo $row['guardianEmail'] ?></p>

        <a href="edit.php?studentID=<?=$studentID?>">Edit Profile</a>
        <a href="editPassword.php?studentID=<?=$studentID?>">
        Change Password</a>

        <a href="../../prices.php">Room Prices</a>
        <a href="../home.php">Go Back</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>