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


    /*check if the form has been submitted*/
    if(isset($_POST['submit']))
    {
        //check if the administrator name entered is in the database
        if(adminNameFound($pdo)==false)
        {
            $_SESSION['error']="The name entered is wrong.";
            header("Location: login.php");
            return;
        }

        //check if the administrator password entered is in the database
        if(adminPasswordFound($pdo)==false)
        {
            $_SESSION['error']="The password entered is wrong.";
            header("Location: login.php");
            return;
        }

        //store the administrator information inside the session
        $_SESSION['adminName']=htmlentities($_POST['adminName']);
        $_SESSION['adminPassword']=htmlentities($_POST['adminPassword']);

        //get the administrator information
        $row=getAdministratorRow($pdo);

        //store the remaining administrator information inside the session
        $_SESSION['adminEmail']=htmlentities($row['adminEmail']);
        $_SESSION['adminPhoneNumber']=
        htmlentities($row['adminPhoneNumber']);


        //redirect with a success message
        $_SESSION['success']="Login was successful!";
        header("Location: home.php");
        return;

    }



?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../style.css">
        <title>Administrator Log In</title>
        <style>

        </style>
    </head>
        <h1>Administrator Log In</h1>
        <h3>Please provide the following information:</h3>

        <form method="post" action="">
            <label for="adminName">Administrator Name: </label>
            <input type="text" name="adminName" id="adminName" 
            required><br>

            <label for="adminPassword">Administrator Password: </label>
            <input type="password" name="adminPassword" 
            id="adminPassword" required><br>

            <input type="submit" name="submit" value="Log In">
        </form>

        <a href="../home.php">Go Back</a>
    <body>
        
    </body>
</html>