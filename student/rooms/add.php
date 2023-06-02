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

    //check if the form has been submitted
    if(isset($_POST['submit']))
    {
        $_SESSION['startDate']=$_POST['startDate'];
        $_SESSION['endDate']=$_POST['endDate'];
        $_SESSION['foodStatus']=$_POST['foodStatus'];
        $_SESSION['roomNumber']=$_POST['roomNumber'];

        /*check if the reservation start Date was set further than its 
        end Date*/
        if($_POST['startDate']>$_POST['endDate'])
        {
            $_SESSION['error']="Your reservation starting date cannot be 
            further ahead in time than your reservation ending date.";
            header("Location: add.php");
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


        $totalFee=$duration*($regularFee + $mealFee*$meals);


        //insert a row in the booking table
        $sql="INSERT INTO booking(duration, startDate, endDate, totalFee, 
        foodStatus, studentID, roomNumber) 
        VALUES(:duration, :startDate, :endDate, :totalFee, :foodStatus, 
        :studentID, :roomNumber);";

        $stmt=$pdo->prepare($sql);

        $stmt->execute(array(
            ":studentID" => $_SESSION['studentID'],
            ":roomNumber" => $_POST['roomNumber'],
            ":duration" => $duration,
            ":startDate" => $_POST['startDate'],
            ":endDate" => $_POST['endDate'],
            ":totalFee" => $totalFee,
            ":foodStatus" => $_POST['foodStatus']
        ));

        //increase the number of students in the room by 1
        incrementNumberOfStudents($pdo);

        //Send a message notifying that the booking was made
        $_SESSION['success']="The room was booked successfully!";
        
        //redirect to view.php
        header("Location: view.php");
        return;
        
    }

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Student Room Booking</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Student Room Booking</h1>
        <h3>Please fill in all the fields in the form below to complete 
            your room reservation:</h3>
        
        <form method="post" action="">

        <!-- Room and Booking Information -->
        <p>Please tell us about the room you want to book:</p>

        <?php 
        /*get today's date to set it as a minimum start Date and 
        end Date for the reservation*/
        $min = date('Y-m-d');
        ?>

        <p>How long are you staying at our hostel?</p>
        <label for="startDate">Reservation Starting Date: </label>
        <input type="date" name="startDate" id="startDate" 
        min="<?= $min ?>" 
        value="<?= isset($_SESSION['startDate']) ? $_SESSION['startDate'] : '' ?>" 
        required><br>

        <label for="endDate">Reservation Ending Date: </label>
        <input type="date" name="endDate" id="endDate" min="<?= $min ?>" 
        value="<?= isset($_SESSION['endDate']) ? $_SESSION['endDate'] : '' 
        ?>" required><br>

        <label for="roomNumber">Room Number</label>
        <select name="roomNumber" id="roomNumber" required>
        <?php 
        /*get the room numbers of the rooms that do not have 
        any students residing in them*/
        $stmt = getFreeRoomNumbers($pdo);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            //display the room numbers as select options
            $roomNumber = (int)$row['roomNumber'];
            $selected = (isset($_SESSION['roomNumber']) && 
            $_SESSION['roomNumber'] == $roomNumber) ? 'selected' : '';
            echo '<option value="' . $roomNumber . '" ' . $selected . '>' 
            . $roomNumber . '</option>'; 
        }
        ?>

        </select><br>

        <p>Do you want to have breakfast, lunch or dinner included?</p>
        <label for="foodStatus">Choose one of the options below: </label>
        <select name="foodStatus" id="foodStatus" required>
        
        <option value="Just Breakfast" 
        <?= (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
        == 'Just Breakfast') ? 'selected' : '' ?>>Just Breakfast</option>
        
        <option value="Just Lunch" 
        <?= (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] == 
        'Just Lunch') ? 'selected' : '' ?>
        >Just Lunch</option>
        
        <option value="Just Dinner" 
        <?= (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] == 
        'Just Dinner') ? 'selected' : '' ?>>Just Dinner</option>
        
        <option value="Breakfast and Lunch" 
        <?= (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
        == 'Breakfast and Lunch') ? 'selected' : '' ?>
        >Breakfast and Lunch</option>
        
        <option value="Breakfast and Dinner" 
        <?= (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
        == 'Breakfast and Dinner') ? 'selected' : '' ?>
        >Breakfast and Dinner</option>
        
        <option value="Lunch and Dinner" 
        <?= (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
        == 'Lunch and Dinner') ? 'selected' : '' ?>
        >Lunch and Dinner</option>
        
        <option value="Breakfast, Lunch and Dinner" 
        <?= (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
        == 'Breakfast, Lunch and Dinner') ? 'selected' : '' ?>
        >Breakfast, Lunch and Dinner</option>
        
        <option value="No food" <?= (isset($_SESSION['foodStatus']) 
        && $_SESSION['foodStatus'] == 'No food') ? 'selected' : '' ?>
        >I do not want any meals</option>
        
        </select><br>

        <input type="submit" name="submit" value="Finish Reservation">
        </form>


        <a href="../../prices.php">Room Prices</a>
        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>