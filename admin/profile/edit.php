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
        //save the old administrator name
        $oldAdminName=$_SESSION['adminName'];
        //save the posted information for redisplaying purposes
        $_SESSION['adminName']=htmlentities($_POST['adminName']);
        $_SESSION['adminPassword']=htmlentities($_POST['adminPassword']);
        $_SESSION['adminEmail']=htmlentities($_POST['adminEmail']);
        $_SESSION['adminPhoneNumber']=
        htmlentities($_POST['adminPhoneNumber']);
        $_SESSION['adminPasswordCopy']=
        htmlentities($_POST['adminPasswordCopy']);

        //check if the new password has not been confirmed correctly
        if($_POST['adminPassword']!=$_POST['adminPasswordCopy'])
        {
            $_SESSION['error']="You did not enter the same password twice, 
            thus failing to confirm your new password.";
            header("Location: edit.php");
            return;
        }

        //update the row in the administrator table
        updateAdministratorRow($pdo, $oldAdminName);

        //let the user know that the profile was updated and redirect
        $_SESSION['success']="Your profile was updated successfully!";
        header("Location: view.php");
        return;

    }

    //get the administrator information
    $row=getAdministratorRow($pdo);

?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Administrator Log In Information Update</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Administrator Log In Information Update</h1>
        <h3>Enter the new log in credentials below:</h3>

        <form method="post" action="">
    
        <label for="adminName">Name: </label>
        <input type="text" name="adminName" id="adminName" 
        <?php echo (isset($_SESSION['adminName'])) ? 'value="' . 
        $_SESSION['adminName'] . '"' : 'value="' . $row['adminName'] . '"';
         ?> required><br>

    
        <p>Please enter a password:</p>
        <label for="adminPassword">Password: </label>
        <input type="password" name="adminPassword" id="adminPassword" 
        <?php echo (isset($_SESSION['adminPassword'])) ? 'value="' . 
        $_SESSION['adminPassword'] . '"' : ''; ?> required><br>

        <p>Please Confirm Your Password:</p>
        <label for="adminPasswordCopy">Password: </label>
        <input type="password" name="adminPasswordCopy" 
        id="adminPasswordCopy" required><br>

        <label for="adminEmail">Email: </label>
        <input type="text" name="adminEmail" id="adminEmail" 
        <?php echo (isset($_SESSION['adminEmail'])) ? 'value="' . 
        $_SESSION['adminEmail'] . '"' : 'value="' . $row['adminEmail'] . 
        '"'; ?> required>

        <label for="adminPhoneNumber">Phone Number: </label>
        <input type="text" name="adminPhoneNumber" id="adminPhoneNumber" 
        <?php echo (isset($_SESSION['adminPhoneNumber'])) ? 'value="' . 
        $_SESSION['adminPhoneNumber'] . '"' : 'value="' . 
        $row['adminPhoneNumber'] . '"'; ?> required>


        <input type="submit" name="submit" value="Update Profile">
        </form>

        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
        
    </body>
</html>