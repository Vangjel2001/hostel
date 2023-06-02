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
        //store the submitted information for redisplaying purposes
        $_SESSION['mealFee']=$_POST['mealFee'];
        $_SESSION['regularFee']=$_POST['regularFee'];
        $_SESSION['capacity']=$_POST['capacity'];

        $roomNumber=$_POST['roomNumber'];

        //update the room in the room table
        updateRoom($pdo);

        //redirect to view.php with a success message
        $_SESSION['success']="The room information was updated 
        successfully!";

        header("Location: view.php");
        return;
    }

    //get the room information
    $row=getRoomInfo($_GET['roomNumber'], $pdo);

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Edit Room</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Edit Room</h1>
        <form method="post" action="">

            <label for="mealFee">Meal Fee: </label>
            <input type="number" name="mealFee" id="mealFee" 
            <?php 
                if(isset($_SESSION['mealFee']))
                {
                    $value=$_SESSION['mealFee'];
                    echo 'value="'.$value.'"';
                }else
                {
                    $value=$row['mealFee'];
                    echo 'value="'.$value.'"';
                }
            ?>
            min="0" required><br>

            <label for="regularFee">Base Fee: </label>
            <input type="number" name="regularFee" id="regularFee"
            <?php 
                if(isset($_SESSION['regularFee']))
                {
                    $value=$_SESSION['regularFee'];
                    echo 'value="'.$value.'"';
                }else
                {
                    $value=$row['regularFee'];
                    echo 'value="'.$value.'"';
                }
            ?>
            min="0" required><br>

            <label for="capacity">Capacity: </label>
            <input type="number" name="capacity" id="capacity" 
            <?php 
                if(isset($_SESSION['capacity']))
                {
                    $value=$_SESSION['capacity'];
                    echo 'value="'.$value.'"';
                }else
                {
                    $value=$row['capacity'];
                    echo 'value="'.$value.'"';
                }
            ?>
            min="1" required><br>
            
            <input type="hidden" name="roomNumber"
            value="<?=$_GET['roomNumber']?>">

            <input type="submit" name="submit" value="Update Room">
        </form>

        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>