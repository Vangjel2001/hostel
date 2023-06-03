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
    if(isset($_POST['submit']) && isset($_POST['studentID']) && 
    isset($_POST['roomNumber']))
    {
        $studentID=$_POST['studentID'];
        $roomNumber=$_POST['roomNumber'];

        $_SESSION['startDate']=$_POST['startDate'];
        $_SESSION['endDate']=$_POST['endDate'];
        $_SESSION['foodStatus']=$_POST['foodStatus'];
        /*check if the reservation start Date was set further than its 
        end Date*/
        if($_POST['startDate']>$_POST['endDate'])
        {
            $_SESSION['error']="Your reservation starting date cannot be 
            further ahead in time than your reservation ending date.";
            header("Location: edit.php?studentID=".$studentID."&roomNumber=".$roomNumber);

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
        $studentID=$_POST['studentID'];
        $roomNumber=$_POST['roomNumber'];

        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $foodStatus=$_POST['foodStatus'];

        //update booking row
        $sql="UPDATE booking 
        SET duration=$duration, startDate='$startDate', 
        endDate='$endDate', totalFee=$totalFee, foodStatus='$foodStatus' 
        WHERE studentID=$studentID AND roomNumber=$roomNumber;";

        $pdo->query($sql);



        //redirect to view.php with a success message
        $_SESSION['success']="The reservation was updated successfully!";

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

    //set today's date as the minimum date
    $minDate=date('Y-m-d');
?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Edit Reservation</title>
        <style>

        </style>
    </head>

    <body>
        <div class="container">
        <h1>Edit Reservation</h1>
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Hostel_Dormitory.jpg">
        <form method="post" action="">

            <label for="startDate">Reservation Start Date: </label>
            <input type="date" name="startDate" id="startDate" 
            min="<?=$minDate?>" 
            <?php 
                if(isset($_SESSION['startDate']))
                {
                    $value=$_SESSION['startDate'];
                    echo 'value="'.$value.'"';
                }else
                {
                    $value=$row['startDate'];
                    echo 'value="'.$value.'"';
                }
            
            ?>
            required><br>

            <label for="endDate">Reservation End Date: </label>
            <input type="date" name="endDate" id="endDate" 
            min="<?=$minDate?>" 
            <?php 
                if(isset($_SESSION['endDate']))
                {
                    $value=$_SESSION['endDate'];
                    echo 'value="'.$value.'"';
                }else
                {
                    $value=$row['endDate'];
                    echo 'value="'.$value.'"';
                }
            
            ?>
            required><br>
            

            <p>Do you want to have breakfast, lunch or dinner included?</p>
            <label for="foodStatus">Choose one of the options below: </label>
            <select name="foodStatus" id="foodStatus" required>
    
            <option value="Just Breakfast" <?php 
            echo (isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Just Breakfast') || 
            (!isset($_SESSION['foodStatus']) && $row['foodStatus'] == 
            'Just Breakfast') ? 'selected' : ''; ?>
            >Just Breakfast</option>
    
            <option value="Just Lunch" <?php 
            echo (isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Just Lunch') || 
            (!isset($_SESSION['foodStatus']) && $row['foodStatus'] == 
            'Just Lunch') ? 'selected' : ''; ?>
            >Just Lunch</option>
    
            <option value="Just Dinner" <?php 
            echo (isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Just Dinner') || 
            (!isset($_SESSION['foodStatus']) && $row['foodStatus'] == 
            'Just Dinner') ? 'selected' : ''; ?>>Just Dinner</option>
    
            <option value="Breakfast and Lunch" <?php 
            echo (isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Breakfast and Lunch') || 
            (!isset($_SESSION['foodStatus']) && $row['foodStatus'] == 
            'Breakfast and Lunch') ? 'selected' : ''; ?>
            >Breakfast and Lunch</option>
    
            <option value="Breakfast and Dinner" <?php 
            echo (isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Breakfast and Dinner') || 
            (!isset($_SESSION['foodStatus']) && $row['foodStatus'] == 
            'Breakfast and Dinner') ? 'selected' : ''; ?>
            >Breakfast and Dinner</option>
    
            <option value="Lunch and Dinner" <?php 
            echo (isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Lunch and Dinner') || 
            (!isset($_SESSION['foodStatus']) && $row['foodStatus'] == 
            'Lunch and Dinner') ? 'selected' : ''; ?>
            >Lunch and Dinner</option>
    
            <option value="Breakfast, Lunch and Dinner" <?php 
            echo (isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Breakfast, Lunch and Dinner') || 
            (!isset($_SESSION['foodStatus']) && $row['foodStatus'] == 
            'Breakfast, Lunch and Dinner') ? 'selected' : ''; ?>
            >Breakfast, Lunch and Dinner</option>
    
            <option value="No food" <?php 
            echo (isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'No food') || 
            (!isset($_SESSION['foodStatus']) && $row['foodStatus'] == 
            'No food') ? 'selected' : ''; ?>
            >I do not want any meals</option>


            </select><br>

            <input type="hidden" name="studentID" 
            value="<?=$_GET['studentID']?>">
            <input type="hidden" name="roomNumber"
            value="<?=$_GET['roomNumber']?>">

            <input type="submit" name="submit" value="Update Reservation">
        </form>

        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
        </div>
    </body>


</html>