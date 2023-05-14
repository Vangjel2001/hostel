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

    //check if the form has been submitted
    if(isset($_POST['submit']) && isset($_POST['studentID']) && 
    isset($_POST['roomNumber']))
    {
        /*check if the reservation start Date was set further than its 
        end Date*/
        if($_POST['startDate']>$_POST['endDate'])
        {
            $_SESSION['error']="Your reservation starting date cannot be 
            further ahead in time than your reservation ending date.";
            header("Location: edit.php");
            return;
        }

        //get the number of days the student is staying in the hostel
        $duration=getDateDifferenceInDays($_POST['startDate'], 
        $_POST['endDate']);

        //get the number of meals the student wants per day in the hostel
        $meals=getMeals();

        //get the room details of the room where the student wants to stay
        $row=getRoomInfo($_POST['roomNumber'], $pdo);
        
        //calculate the total amount needed to be paid
        $regularFee=$row['regularFee'];
        $mealFee=$row['mealFee'];


        //calculate the price that the student should pay
        $totalFee=$duration*($regularFee + $mealFee*$meals);
        
        //store values in variables with shorter names
        $studentID=$_SESSION['studentID'];

        $newRoomNumber=$_POST['roomNumber'];
        $oldRoomNumber=$_SESSION['roomNumber'];

        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $foodStatus=$_POST['foodStatus'];

        //update booking row
        $sql="UPDATE booking 
        SET roomNumber=$newRoomNumber, duration=$duration, 
        startDate='$startDate', endDate='$endDate', totalFee=$totalFee, 
        foodStatus='$foodStatus' 
        WHERE studentID=$studentID AND roomNumber=$oldRoomNumber;";

        $pdo->query($sql);

        $_SESSION['success']="Your reservation was updated successfully!";
        
        //delete the old roomNumber from $_SESSION
        unset($_SESSION['roomNumber']);

        header("Location: view.php");
        return;
    }

    //check if the user has typed edit.php as a url
    if(missingGetParameters("edit.php"))
    {
        header("Location: view.php");
        return;
    }

    /*check if the user has typed edit.php as a url with inappropriate get 
    parameters following it*/
    if(wrongGetParameters("edit.php", $pdo))
    {
        header("Location: view.php");
        return;
    }

    //get the details of the reservation
    $row=getBookingInfo($pdo);

    //store the old roomNumber inside $_SESSION
    $roomNumber=$row['roomNumber'];
    $_SESSION['roomNumber']=$roomNumber;

    //store the reservation details in variables with shorter names
    $startDate=$row['startDate'];
    $endDate=$row['endDate'];
    $foodStatus=$row['foodStatus'];

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" 
        href="file:///C:/xampp/htdocs/hostel/style.css">
        <title>Reservation Update</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Reservation Update</h1>
        <h3>Please fill in all the fields in the form below to complete 
            your room reservation update:</h3>
        <form method="post" action="">

            <!-- Room and Booking Information -->

            <p>How long are you staying at our hostel?</p>
            <label for="startDate">Reservation Starting Date: </label>
            <input type="date" name="startDate" id="startDate" 
            value="<?=$startDate ?>" required>
            <br>

            <label for="endDate">Reservation Ending Date: </label>
            <input type="date" name="endDate" id="endDate" 
            value="<?=$endDate ?>" required>
            <br>

            <label for="roomNumber">Room Number</label>
            <select name="roomNumber" id="roomNumber" required>

            <option value="<?=$_GET['roomNumber']?>"> 
                <?=$_GET['roomNumber']?></option> 
                <?php
                /*get the room numbers of the rooms that do not have 
                any students residing in them*/
                    $stmt=getFreeRoomNumbers($pdo);
                    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        //display the room numbers as select options
                        $roomNumber=(int)$row['roomNumber'];
                        echo '<option value="'.$roomNumber.'">'.
                        $roomNumber.'</option>'; 
                    }
                ?>

            </select><br>

            <p>Do you want to have breakfast, lunch or dinner included?</p>
            <label for="foodStatus">Choose one of the options below: </label>
            <select name="foodStatus" id="foodStatus" required>
                <option value="Just Breakfast">Just Breakfast</option>
                <option value="Just Lunch">Just Lunch</option>
                <option value="Just Dinner">Just Dinner</option>
                <option value="Breakfast and Lunch">Breakfast 
                    and Lunch</option>
                <option value="Breakfast and Dinner">Breakfast and Dinner</option>
                <option value="Lunch and Dinner">Lunch and Dinner</option>
                <option value="Breakfast, Lunch and Dinner">Breakfast, Lunch 
                    and Dinner</option>
                <option value="No food">I do not want any meals</option>
            </select><br>

            <input type="hidden" name="studentID" 
            value=<?=$_GET['studentID']?>>
            <input type="hidden" name="roomNumber" 
            value=<?=$_GET['roomNumber']?>>
            <input type="submit" name="submit" value="Update Reservation">
        </form>

        <a href="view.php">Cancel</a>
    </body>
</html>