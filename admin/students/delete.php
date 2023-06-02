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
        //get the guardianID of the student
        $guardianID=getGuardianID($_POST['studentID'], $pdo);

        //get the student information
        $sql="SELECT * 
        FROM student 
        WHERE guardianID=$guardianID;";
        $stmt=$pdo->query($sql);

        /*check if there is no more than 1 student that has that 
        guardian*/
        if($stmt->rowCount()<=1)
        {
            //delete the row from the guardian table
            $sql="DELETE FROM guardian 
            WHERE guardianID=$guardianID;";
            $pdo->query($sql);
            $_SESSION['success']="The guardian was successfully deleted!";
        }

        //store the studentID
        $studentID=$_POST['studentID'];

        //get the booking rows of the student
        $sql="SELECT * 
        FROM booking 
        WHERE studentID=$studentID;";

        $stmt=$pdo->query($sql);

        
        //check if the student has made any bookings
        if($stmt->rowCount()>0)
        {
            //delete the rows from the booking table
            $sql="DELETE FROM booking 
            WHERE studentID=$studentID;";
            $pdo->query($sql);

            if(isset($_SESSION['success']))
            $_SESSION['success'].=" The student's bookings were 
            successfully deleted!";
            else
            $_SESSION['success']="The student's bookings were 
            successfully deleted!";
        }

        
        //delete the row from the student table
        $sql="DELETE FROM student 
        WHERE studentID=$studentID;";
        $pdo->query($sql);


        /*check if a success message regarding the deletion of 
        an administrator exists
        and redirect to view.php with a success message*/
        if(isset($_SESSION['success']))
        $_SESSION['success'].=" The student was successfully deleted!";
        else
        $_SESSION['success']="The student was successfully deleted!";
        header("Location: view.php");
        return;
    }

    //get the student information
    $row=getStudentRow($pdo);

    //store the student information
    $studentID=$row['studentID'];
    $studentName=$row['studentName'];
    $studentSurname=$row['studentSurname'];
    $studentEmail=$row['studentEmail'];

?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Deleting Student</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Deleting Student</h1>
        <h3>Are you sure you want to delete the student with the 
            following information?</h3>
        
        <p>Student ID: <?=$studentID?></p>
        <p>Student Name: <?=$studentName?></p>
        <p>Student Surname: <?=$studentSurname?></p>
        <p>Student Email: <?=$studentEmail?></p>

        <form method="post" action="">
            <input type="hidden" name="studentID" 
            value="<?=$_GET['studentID'] ?>">
            <input type="submit" name="submit" value="Delete Student">
        </form>

        <a href="view.php">Go Back</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>