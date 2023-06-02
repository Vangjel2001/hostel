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


    if(isset($_POST['submit']))
    {
        /*initialize the variables to store form input values for 
        redisplaying purposes*/
        $_SESSION['studentName'] = htmlspecialchars($_POST['studentName']);
        $_SESSION['studentSurname'] = 
        htmlspecialchars($_POST['studentSurname']);
        $_SESSION['studentEmail'] = 
        htmlspecialchars($_POST['studentEmail']);
        $_SESSION['studentPhoneNumber'] = 
        htmlspecialchars($_POST['studentPhoneNumber']);
        $_SESSION['studentGender'] = 
        htmlspecialchars($_POST['studentGender']);
        $_SESSION['guardianRelation'] = 
        htmlspecialchars($_POST['guardianRelation']);
        $_SESSION['studentAge'] = htmlspecialchars($_POST['studentAge']);
        $_SESSION['education'] = htmlspecialchars($_POST['education']);
        $_SESSION['studentAddress'] = 
        htmlspecialchars($_POST['studentAddress']);
        $_SESSION['studentPassword'] = 
        htmlspecialchars($_POST['studentPassword']);
        $_SESSION['studentPasswordCopy'] =
        htmlspecialchars($_POST['studentPasswordCopy']);

        $_SESSION['guardianName'] = 
        htmlspecialchars($_POST['guardianName']);
        $_SESSION['guardianSurname'] = 
        htmlspecialchars($_POST['guardianSurname']);
        $_SESSION['guardianPhoneNumber'] = 
        htmlspecialchars($_POST['guardianPhoneNumber']);
        $_SESSION['guardianEmail'] = 
        htmlspecialchars($_POST['guardianEmail']);
        $_SESSION['guardianAge'] = 
        htmlspecialchars($_POST['guardianAge']);


        $studentID=$_POST['studentID'];

        //check if the guardian is underage
        if($_POST['guardianAge']<18)
        {
            $_SESSION['error']="The advocate should be 18 years old 
            or older. Try entering another advocate.";
            header("Location: edit.php?studentID=$studentID");
            return;
        }
        //check if the guardian has the same phone number as the student
        if($_POST['guardianPhoneNumber']==$_POST['studentPhoneNumber'])
        {
            $_SESSION['error']="The advocate should have a different 
            phone number from the student's.";
            header("Location: edit.php?studentID=$studentID");
            return;
        }

        //check if the password has not been confirmed correctly
        if($_POST['studentPassword']!==$_POST['studentPasswordCopy'])
        {   
            $_SESSION['error']="You did not confirm your entered password 
            correctly. Please try entering your password correctly 
            twice.";
            header("Location: edit.php?studentID=$studentID");
            return;
        }

        //get the guardianID
        $guardianID=getGuardianID($_POST['studentID'], $pdo);

        //update guardian in the guardian table
        updateGuardianRow($pdo, $guardianID);
    

        //update student in the student table
        updateStudentRow($pdo, $_POST['studentID']);


        //Write a success message and redirect to view.php
        $_SESSION['success']="The student profile was updated 
        successfully!";
        header("Location: view.php");
        return;
    }

    /*check if the studentID has not been passed in the URL from 
    view.php*/
    if(empty($_GET['studentID']))
    {
        $_SESSION['error']="The studentID is missing in the URL.";
        header("Location: view.php");
        return;
    }

    //get the student and guardian information
    $row=getStudentAndGuardianRow($_GET['studentID'], $pdo);


?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Student Profile Edit</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Student Profile</h1>
        <form method="post">

            <!--Student Information-->
        <label for="studentName">Name: </label>
        <input type="text" name="studentName" id="studentName"
        <?php if(isset($_SESSION['studentName'])) { 
            echo 'value="' . $_SESSION['studentName'] . '"'; 
            }
            else
            {
                echo 'value="' . $row['studentName'] . '"';
            }
            ?> required><br>

        <label for="studentSurname">Surname: </label>
        <input type="text" name="studentSurname" id="studentSurname"
        <?php if(isset($_SESSION['studentSurname'])) { 
        echo 'value="' . $_SESSION['studentSurname'] . '"'; 
        }
            else
            {
                echo 'value="' . $row['studentSurname'] . '"';
            }
        ?> required><br>

        <label for="studentEmail">Email: </label>
        <input type="email" name="studentEmail" id="studentEmail"
        <?php if(isset($_SESSION['studentEmail'])) { 
        echo 'value="' . $_SESSION['studentEmail'] . '"'; 
        }
            else
            {
                echo 'value="' . $row['studentEmail'] . '"';
            }
        ?> required><br>

        <label for="studentPhoneNumber">Phone Number: </label>
        <input type="text" name="studentPhoneNumber" id="studentPhoneNumber"
        <?php if(isset($_SESSION['studentPhoneNumber'])) { 
        echo 'value="' . $_SESSION['studentPhoneNumber'] . '"'; 
        }
        else
        {
            echo 'value="' . $row['studentPhoneNumber'] . '"';
        }
        ?> required><br>


        <label for="studentGender">Gender: </label>
        <select name="studentGender" id="studentGender" required>
        
        <option value="Male" <?php echo (isset($_SESSION['studentGender']) 
        && $_SESSION['studentGender'] == 'Male') ? 'selected' : 
        ($row['studentGender'] == 'Male' ? 'selected' : ''); ?>
        >Male</option>
        
        <option value="Female" <?php echo 
        (isset($_SESSION['studentGender']) && $_SESSION['studentGender'] 
        == 'Female') ? 'selected' : ($row['studentGender'] == 'Female' ? 
        'selected' : ''); ?>>Female</option>
        
        <option value="Other" <?php echo 
        (isset($_SESSION['studentGender']) && $_SESSION['studentGender'] 
        == 'Other') ? 'selected' : ($row['studentGender'] == 'Other' ? 
        'selected' : ''); ?>>Other</option>
        
        </select><br>



        <label for="studentAge">Age: </label>
        <input type="number" name="studentAge" id="studentAge"
        <?php if(isset($_SESSION['studentAge'])) { 
        echo 'value="' . $_SESSION['studentAge'] . '"'; 
        }
        else
        {
            echo 'value="' . $row['studentAge'] . '"';
        }
        ?> required><br>


        <label for="education">Current Education level: </label>
        <select name="education" id="education" required>
    
        <option value="High School" <?php echo 
        (isset($_SESSION['education']) && $_SESSION['education'] == 
        'High School') ? 'selected' : ($row['education'] == 'High School' 
        ? 'selected' : ''); ?>>I am a high school student.</option>
    
        <option value="Bachelor" <?php echo 
        (isset($_SESSION['education']) && $_SESSION['education'] == 
        'Bachelor') ? 'selected' : ($row['education'] == 'Bachelor' ? 
        'selected' : ''); ?>>I am a bachelor student.</option>
    
        <option value="Master" <?php echo (isset($_SESSION['education']) 
        && $_SESSION['education'] == 'Master') ? 'selected' : 
        ($row['education'] == 'Master' ? 'selected' : ''); ?>
        >I am a master student.</option>
    
        <option value="PHD" <?php echo (isset($_SESSION['education']) 
        && $_SESSION['education'] == 'PHD') ? 'selected' : 
        ($row['education'] == 'PHD' ? 'selected' : ''); ?>
        >I am a phd student</option>

        </select><br>



        <label for="studentAddress">Please write your address below:</label><br>

        <textarea name="studentAddress" id="studentAddress" cols="30" 
        rows="10" required>
        <?php if(isset($_SESSION['studentAddress'])) { 
        echo $_SESSION['studentAddress']; 
        } 
            else
            {
                echo $row['studentAddress'];
            }
        ?>
        </textarea><br>

        <p>Please enter a password:</p>

        <!-- Guardian Information-->
        <p>We need a person (like your father for example) that you can 
        enter as your advocate.</p>
        <label for="guardianName">Advocate Name: </label>
        <input type="text" name="guardianName" id="guardianName" required

        <?php if(isset($_SESSION['guardianName'])) { 
        echo 'value="' . $_SESSION['guardianName'] . '"'; 
        }
        else
        {
            echo 'value="' . $row['guardianName'] . '"';
        }
        ?>><br>

        <label for="guardianSurname">Advocate Surname: </label>
        <input type="text" name="guardianSurname" id="guardianSurname" 
        required

        <?php if(isset($_SESSION['guardianSurname'])) { 
        echo 'value="' . $_SESSION['guardianSurname'] . '"'; 
        }
            else
            {
                echo 'value="' . $row['guardianSurname'] . '"';
            }
        ?>><br>

        <label for="guardianAge">Advocate Age: </label>
        <input type="number" name="guardianAge" id="guardianAge" required

        <?php if(isset($_SESSION['guardianAge'])) { 
        echo 'value="' . $_SESSION['guardianAge'] . '"'; 
        }
            else
            {
                echo 'value="' . $row['guardianAge'] . '"';
            }
        ?>><br>

        <label for="guardianPhoneNumber">Advocate Phone Number: </label>
        <input type="text" name="guardianPhoneNumber" 
        id="guardianPhoneNumber" required

        <?php if(isset($_SESSION['guardianPhoneNumber'])) { 
        echo 'value="' . $_SESSION['guardianPhoneNumber'] . '"'; 
        }
            else
            {
                echo 'value="' . $row['guardianPhoneNumber'] . '"';
            }
        ?>><br>

        <label for="guardianEmail">Advocate Email: </label>
        <input type="email" name="guardianEmail" id="guardianEmail" 
        required

        <?php if(isset($_SESSION['guardianEmail'])) { 
        echo 'value="' . $_SESSION['guardianEmail'] . '"'; 
        }
        else
        {
            echo 'value="' . $row['guardianEmail'] . '"';
        }
        ?>><br>


        <p>What is your relationship with this person?</p>
        <label for="guardianRelation">This person is my: </label>
        <select name="guardianRelation" id="guardianRelation" required>
    
        <option value="Brother" <?php echo 
        (isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Brother') ? 'selected' : 
        ($row['guardianRelation'] == 'Brother' ? 'selected' : ''); ?>
        >Brother</option>
    
        <option value="Sister" <?php echo 
        (isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Sister') ? 'selected' : 
        ($row['guardianRelation'] == 'Sister' ? 'selected' : ''); ?>
        >Sister</option>
    
        <option value="Mother" <?php 
        echo (isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Mother') ? 'selected' : 
        ($row['guardianRelation'] == 'Mother' ? 'selected' : ''); ?>
        >Mother</option>
    
        <option value="Father" <?php echo 
        (isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Father') ? 'selected' : 
        ($row['guardianRelation'] == 'Father' ? 'selected' : ''); ?>
        >Father</option>
    
        <option value="Other Relationship" <?php 
        echo (isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Other Relationship') ? 
        'selected' : ($row['guardianRelation'] == 'Other Relationship' ? 
        'selected' : ''); ?>>Other Relationship</option>

        </select><br>

        <input type="hidden" name="studentID" 
        value="<?=$_GET['studentID'] ?>">
        <input type="submit" name="submit" 
        value="Update Student Profile">
        </form>

        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
    </body>
</html>