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
        $_SESSION['roomNumber']=$_POST['roomNumber'];
        $_SESSION['mealFee']=$_POST['mealFee'];
        $_SESSION['regularFee']=$_POST['regularFee'];
        $_SESSION['capacity']=$_POST['capacity'];

        //Check if the roomNumber is a multiple of 100
        if($_POST['roomNumber']%100==0)
        {
            $_SESSION['error']="The room numbers cannot be 100, 200, 
            300 etc...(multiples of 100).";
            header("Location: add.php");
            return;
        }

        //get all roomNumbers
        $stmt=getAllRoomNumbers($pdo);

        /*check if the roomNumber of the new room is the same as 
        the roomNumber of an existing room*/
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            if($_POST['roomNumber']==$row['roomNumber'])
            {
                $_SESSION['error']="A room with that room number 
                already exists.";
                header("Location: add.php");
                return;
            }
        }

        //add the new room in the room table
        addRoom($pdo);

        //redirect to view.php with a success message
        $_SESSION['success']="The room was successfully added!";
        header("Location: view.php");
        return;
    }


?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Add A Room</title>
        <style>

        </style>
    </head>

    <body>
        <div class="container">
        <h1>Add A Room</h1>
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Hostel_Dormitory.jpg">
        <form method="post" action="">
            <label for="roomNumber">Room Number: </label>
            <input type="number" name="roomNumber" id="roomNumber" 
            <?php 
                if(isset($_SESSION['roomNumber']))
                {
                    $value=$_SESSION['roomNumber'];
                    echo 'value="'.$value.'"';
                }
            ?>
            min="1" max="999" required><br>

            <label for="mealFee">Meal Fee: </label>
            <input type="number" name="mealFee" id="mealFee" 
            <?php 
                if(isset($_SESSION['mealFee']))
                {
                    $value=$_SESSION['mealFee'];
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
                }
            ?>
            min="1" required><br>

            <input type="submit" name="submit" value="Add Room">
        </form>

        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
        </div>
    </body>
</html>