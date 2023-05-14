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

    if(isset($_POST['submit']))
    {
        //check if the guardian is underage
        if($_POST['guardianAge']<18)
        {
            $_SESSION['error']="Your guardian should be 18 years old 
            or older. Try entering another guardian.";
            header("Location: edit.php");
            return;
        }
        //check if the guardian has the same phone number as the student
        if($_POST['guardianPhoneNumber']==$_POST['studentPhoneNumber'])
        {
            $_SESSION['error']="Your guardian should have a different 
            phone number from yours.";
            header("Location: edit.php");
            return;
        }

        //update guardian in the guardian table
        updateGuardianRow($pdo, $_SESSION['guardianID']);
        

        //update student in the student table
        updateStudentRow($pdo, $_SESSION['studentID'], 
        $_SESSION['guardianID']);


        /*Write a success message and send the signed up student 
        to the student home page*/
        $_SESSION['success']="Your profile was updated successfully!";
        header("Location: view.php");
        return;
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" 
        href="file:///C:/xampp/htdocs/hostel/style.css">
        <title>Student Profile Edit</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Student Profile</h1>
        <form method="post">

            <!--Student Information-->
            <label for="studentName">Name: </label>
            <input type="text" name="studentName" id="studentName" required>
            <br>

            <label for="studentSurname">Surname: </label>
            <input type="text" name="studentSurname" id="studentSurname" 
            required><br>

            <label for="studentPhoneNumber">Phone Number: </label>
            <input type="text" name="studentPhoneNumber" 
            id="studentPhoneNumber" required><br>
            

            <label for="studentGender">Gender: </label>
            <select name="studentGender" id="studentGender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select><br>

            <label for="studentAge">Age: </label>
            <input type="number" name="studentAge" id="studentAge" 
            required><br>

            <label for="education">Current Education level: </label>
            <select name="education" id="education" required>
                <option value="High School">
                    I am a high school student.</option>
                <option value="Bachelor">
                    I am a bachelor student.</option>
                <option value="Master">I am a master student.</option>
                <option value="PHD">I am a phd student</option>
            </select><br>

            <label for="studentAddress">
                Please write your address below:</label><br>
            <textarea name="studentAddress" id="studentAddress" 
            cols="30" rows="10" required></textarea><br>


            <!-- Guardian Information-->
            <p>We need a person (like your father for example) that
                 you can enter as your guardian.</p>

            <label for="guardianName">Name: </label>
            <input type="text" name="guardianName" id="guardianName" 
            required><br>

            <label for="guardianSurname">Surname: </label>
            <input type="text" name="guardianSurname" 
            id="guardianSurname" required><br>

            <label for="guardianAge">Age: </label>
            <input type="number" name="guardianAge" id="guardianAge" 
            required><br>

            <label for="guardianPhoneNumber">Phone Number: </label>
            <input type="text" name="guardianPhoneNumber" 
            id="guardianPhoneNumber" required><br>

            <label for="guardianEmail">Email: </label>
            <input type="email" name="guardianEmail" id="guardianEmail" 
            required><br>

            <p>What is your relationship with this person?</p>
            <label for="guardianRelation">This person is my: </label>
            <select name="guardianRelation" id="guardianRelation" 
            required>
                <option value="Brother">Brother</option>
                <option value="Sister">Sister</option>
                <option value="Mother">Mother</option>
                <option value="Father">Father</option>
                <option value="Other Relationship">Other Relationship
                </option>    
            </select><br>

            <input type="submit" name="submit" value="Update Profile">
        </form>

        <a href="view.php">Cancel</a>
    </body>
</html>