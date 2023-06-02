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
    if(isset($_POST['submit']) && isset($_POST['studentID']) && 
    isset($_POST['roomNumber']))
    {
        $_SESSION['startDate']=$_POST['startDate'];
        $_SESSION['endDate']=$_POST['endDate'];
        $_SESSION['foodStatus']=$_POST['foodStatus'];

        /*check if the reservation start Date was set further than its 
        end Date*/
        if($_POST['startDate']>$_POST['endDate'])
        {
            $_SESSION['error']="Your reservation starting date cannot be 
            further ahead in time than your reservation ending date.";
            header("Location: edit.php?studentID=" . 
            $_POST['studentID'] . "&roomNumber=" . $_POST['roomNumber']);
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

        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $foodStatus=$_POST['foodStatus'];
        $roomNumber=$_POST['roomNumber'];

        //update booking row
        $sql="UPDATE booking 
        SET duration=$duration, startDate='$startDate', 
        endDate='$endDate', totalFee=$totalFee, foodStatus='$foodStatus' 
        WHERE studentID=$studentID AND roomNumber=$roomNumber;";

        $pdo->query($sql);

        $_SESSION['success']="Your reservation was updated successfully!";

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

    //store the reservation details in variables with shorter names
    $startDate=$row['startDate'];
    $endDate=$row['endDate'];
    $foodStatus=$row['foodStatus'];

?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
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
            value="<?php echo isset($_SESSION['startDate']) ? 
            $_SESSION['startDate'] : $startDate; ?>" required>
            <br>

            <label for="endDate">Reservation Ending Date: </label>
            <input type="date" name="endDate" id="endDate" 
            value="<?php echo isset($_SESSION['endDate']) ? 
            $_SESSION['endDate'] : $endDate; ?>" required>
            <br>

            <p>Do you want to have breakfast, lunch or dinner included?</p>
            <label for="foodStatus">Choose one of the options below: 
            </label>
           <!-- <select name="foodStatus" id="foodStatus" required>
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
            </select><br> -->

    
    <select name="foodStatus" id="foodStatus" required>

    <option value="Just Breakfast" 
    <?php echo (isset($_SESSION['foodStatus']) 
    && $_SESSION['foodStatus'] == 'Just Breakfast') ? 'selected' : 
    (($foodStatus == 'Just Breakfast') ? 'selected' : ''); ?>
    >Just Breakfast</option>

    <option value="Just Lunch" 
    <?php echo (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus']
     == 'Just Lunch') ? 'selected' : (($foodStatus == 'Just Lunch') ? 
     'selected' : ''); ?>>Just Lunch</option>
    <option value="Just Dinner" 
    <?php echo (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
    == 'Just Dinner') ? 'selected' : (($foodStatus == 'Just Dinner') ? 
    'selected' : ''); ?>>Just Dinner</option>

    <option value="Breakfast and Lunch" 
    <?php echo (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
    == 'Breakfast and Lunch') ? 'selected' : (($foodStatus == 
    'Breakfast and Lunch') ? 'selected' : ''); ?>
    >Breakfast and Lunch</option>

    <option value="Breakfast and Dinner" 
    <?php echo (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
    == 'Breakfast and Dinner') ? 'selected' : 
    (($foodStatus == 'Breakfast and Dinner') ? 'selected' : ''); ?>
    >Breakfast and Dinner</option>

    <option value="Lunch and Dinner" 
    <?php echo (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
    == 'Lunch and Dinner') ? 'selected' : (($foodStatus == 
    'Lunch and Dinner') ? 'selected' : ''); ?>
    >Lunch and Dinner</option>

    <option value="Breakfast, Lunch and Dinner" 
    <?php echo (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
    == 'Breakfast, Lunch and Dinner') ? 'selected' : 
    (($foodStatus == 'Breakfast, Lunch and Dinner') ? 'selected' : ''); ?>
    >Breakfast, Lunch and Dinner</option>

    <option value="No food" 
    <?php echo (isset($_SESSION['foodStatus']) && $_SESSION['foodStatus'] 
    == 'No food') ? 'selected' : (($foodStatus == 'No food') ? 'selected' :
     ''); ?>>I do not want any meals</option>
    </select><br>

            <input type="hidden" name="studentID" 
            value=<?=$_GET['studentID']?>>
            <input type="hidden" name="roomNumber" 
            value=<?=$_GET['roomNumber']?>>
            <input type="submit" name="submit" value="Update Reservation">
        </form>

        <a href="../../prices.php">Room Prices</a>
        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>