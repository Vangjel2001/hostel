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

    if(isset($_POST['submit']))
    {
        if($_POST['studentPassword']!==
        $_POST['studentPasswordCopy'])
        {
            $_SESSION['error']="You did not enter the same password 
            twice, thus failing to confirm your new password.";
            header("Location: editPassword.php");
            return;
        }

        $studentID=$_SESSION['studentID'];

        $studentPassword=hashedPassword($_POST['studentPassword']);

        $sql="update student
        set studentPassword=:zip
        where studentID=$studentID;";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":zip", $studentPassword);
        $stmt->execute();

        $_SESSION['success']="The password was successfully updated!";
        header("Location: view.php");
        return;
    }

?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Student Password Edit</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Student Password Edit</h1>
        
        <form method="post" action="">

            <p>Please enter a password:</p>
            <label for="studentPassword">Password: </label>
            <input type="password" name="studentPassword" 
            id="studentPassword" required><br>

            <p>Please Confirm Your Password:</p>
            <label for="studentPasswordCopy">Password: </label>
            <input type="password" name="studentPasswordCopy" 
            id="studentPasswordCopy" required><br>

            <input type="submit" name="submit" value="Update Password">
        </form>

        <a href="../../prices.php">Room Prices</a>
        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>