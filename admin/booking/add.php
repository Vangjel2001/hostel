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
        /*store the submitted information inside the session 
        for redisplaying purposes*/
        $_SESSION['studentEmail']=$_POST['studentEmail'];
        $_SESSION['roomNumber']=$_POST['roomNumber'];
        $_SESSION['startDate']=$_POST['startDate'];
        $_SESSION['endDate']=$_POST['endDate'];
        $_SESSION['foodStatus']=$_POST['foodStatus'];


        /*check if a reservation for the same room and the same student 
        exists already*/ 
        if(existingReservation($pdo))
        {
            $_SESSION['error']="You cannot make another booking with 
            the same student and the same room.";
            header("Location: add.php");
            return;
        }


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

        //get the student information from the database
        $row=returnStudentRow($_POST['studentEmail'], $pdo);
        //store the studentID
        $studentID=$row['studentID'];

        //insert a row in the booking table
        $sql="INSERT INTO booking(duration, startDate, endDate, totalFee, 
        foodStatus, studentID, roomNumber) 
        VALUES(:duration, :startDate, :endDate, :totalFee, :foodStatus, 
        :studentID, :roomNumber);";

        $stmt=$pdo->prepare($sql);

        $stmt->execute(array(
            ":studentID" => $studentID,
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

    //get all the student emails
    $stmt=returnStudentEmails($pdo);

    //set today's date as the minimum date
    $minDate=date('Y-m-d');

?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../../style.css">
        <title>Make A Reservation</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Make A Reservation</h1>
        <form method="post" action="">
            <label for="studentEmail">Student Email: </label>
            <select name="studentEmail" id="studentEmail" required>
            <?php 
            // Display all the student emails as select options
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $studentEmail = $row['studentEmail'];
                echo '<option value="' . $studentEmail . '" ' . 
                (isset($_SESSION['studentEmail']) && 
                $_SESSION['studentEmail'] == $studentEmail ? 'selected' : 
                '') . '>' . $studentEmail . '</option>';
            }
            ?>
        </select><br>


            <label for="roomNumber">Room Number: </label>
            <select name="roomNumber" id="roomNumber" required>
            <?php 
            // Get the room numbers of the rooms that are not full
            $stmt = getNotFullRoomNumbers($pdo);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Display the room numbers as select options
                $roomNumber = (int)$row['roomNumber'];
                echo '<option value="' . $roomNumber . '" ' . 
                (isset($_SESSION['roomNumber']) && 
                $_SESSION['roomNumber'] == $roomNumber ? 'selected' : 
                '') . '>' . $roomNumber . '</option>'; 
            }
            ?>
            </select><br>

            <label for="startDate">Reservation Start Date: </label>
            <input type="date" name="startDate" id="startDate" 
            min="<?=$minDate?>" required 
            value="<?=(isset($_SESSION['startDate'])) ? 
            $_SESSION['startDate'] : '' ?>"><br>

            <label for="endDate">Reservation End Date: </label>
            <input type="date" name="endDate" id="endDate" 
            min="<?=$minDate?>" required 
            value="<?=(isset($_SESSION['endDate'])) ? 
            $_SESSION['endDate'] : '' ?>"><br>

            <p>Do you want to have breakfast, lunch or dinner included?</p>
            <label for="foodStatus">Choose one of the options below: 
            </label>
            <select name="foodStatus" id="foodStatus" required>
            
            <option value="Just Breakfast" 
            <?=(isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Just Breakfast') ? 'selected' : 
            '' ?>>Just Breakfast</option>
            
            <option value="Just Lunch" 
            <?=(isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Just Lunch') ? 'selected' : '' ?>
            >Just Lunch</option>
            
            <option value="Just Dinner" 
            <?=(isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Just Dinner') ? 'selected' : '' ?>
            >Just Dinner</option>
    
            <option value="Breakfast and Lunch" 
            <?=(isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Breakfast and Lunch') ? 
            'selected' : '' ?>>Breakfast and Lunch</option>
    
            <option value="Breakfast and Dinner" 
            <?=(isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Breakfast and Dinner') ? 
            'selected' : '' ?>>Breakfast and Dinner</option>
    
            <option value="Lunch and Dinner" 
            <?=(isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Lunch and Dinner') ? 
            'selected' : '' ?>>Lunch and Dinner</option>
    
            <option value="Breakfast, Lunch and Dinner" 
            <?=(isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'Breakfast, Lunch and Dinner') ? 
            'selected' : '' ?>>Breakfast, Lunch and Dinner</option>
    
            <option value="No food" 
            <?=(isset($_SESSION['foodStatus']) && 
            $_SESSION['foodStatus'] == 'No food') ? 'selected' : '' ?>
            >I do not want any meals</option>

            </select><br>

            <input type="submit" name="submit" value="Book Room">
        </form>

        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
    </body>


</html>