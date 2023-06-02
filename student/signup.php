<?php 

    /*require the script from the database connection file
    and the file containing user-defined functions*/
    require_once "../pdo.php";
    require_once "../functions.php";

    //start the session
    session_start();

    /*display flash messages if things have gone right or wrong
    during the use of the application*/
    displayErrorMessage();
    displaySuccessMessage();

    //check if the form has been submitted
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

        $_SESSION['guardianName'] = htmlspecialchars($_POST['guardianName']);
        $_SESSION['guardianSurname'] = htmlspecialchars($_POST['guardianSurname']);
        $_SESSION['guardianPhoneNumber'] = htmlspecialchars($_POST['guardianPhoneNumber']);
        $_SESSION['guardianEmail'] = htmlspecialchars($_POST['guardianEmail']);
        $_SESSION['guardianAge'] = htmlspecialchars($_POST['guardianAge']);


        //check if the student has signed up before
        if(studentNameFound($_POST['studentName'], $pdo) && 
        studentSurnameFound($_POST['studentSurname'], $pdo) && 
        studentEmailFound($_POST['studentEmail'], $pdo))
        {
            $_SESSION['error']="You have already signed up before. Try 
            logging in instead.";
            header("Location: login.php");
            return;
        }

        //check if another student has registered with the same email
        if(studentEmailFound($_POST['studentEmail'], $pdo))
        {
            $_SESSION['error']="Your email has been entered by another 
            student already. Try entering a different email or contact 
            the administrator to report this problem.";
            header("Location: signup.php");
            return;
        }

        //check if the password has not been confirmed correctly
        if($_POST['studentPassword']!==$_POST['studentPasswordCopy'])
        {   
            $_SESSION['error']="You did not confirm your entered password 
            correctly. Please try entering your password correctly 
            twice.";
            header("Location: signup.php");
            return;
        }

        //check if the guardian is underage
        if($_POST['guardianAge']<18)
        {
            $_SESSION['error']="Your guardian should be 18 years old 
            or older. Try entering another guardian.";
            header("Location: signup.php");
            return;
        }

        //check if the guardian has the same email as the student
        if($_POST['guardianEmail']==$_POST['studentEmail'])
        {
            $_SESSION['error']="Your guardian should have a different 
            email from yours.";
            header("Location: signup.php");
            return;
        }

        //check if the guardian has the same phone number as the student
        if($_POST['guardianPhoneNumber']==$_POST['studentPhoneNumber'])
        {
            $_SESSION['error']="Your guardian should have a different 
            phone number from yours.";
            header("Location: signup.php");
            return;
        }

        
        //insert guardian in the guardian table
        insertRowIntoGuardian($pdo);
        
        /*get the auto-generated guardianID from the guardian table 
        in order to insert it as a foreign key into the student table*/
        $guardianRow=returnGuardianRow($_POST['guardianEmail'], $pdo);
        $guardianID=$guardianRow['GuardianID'];
        

        //insert student in the student table
        insertRowIntoStudent($guardianID, $pdo);

        /*save the student email and password inside $_SESSION for 
        later use*/
        $_SESSION['studentEmail']=$_POST['studentEmail'];
        $_SESSION['studentPassword']=$_POST['studentPassword'];

        /*save the guardianID inside $_SESSION for 
        later use*/
        $guardianRow= returnGuardianRow($_POST['guardianEmail'], $pdo);
        $_SESSION['guardianID']= $guardianRow['guardianID'];
        

        //get the auto-generated studentID from the student table
        $row=returnStudentRow($_POST['studentEmail'], $pdo);
        //store it inside $_SESSION for later use
        $_SESSION['studentID']=$row['studentID'];

        /*Write a success message and send the signed up student 
        to the student home page*/
        $_SESSION['success']="Sign Up was successful!";
        header("Location: home.php");
        return;
    }

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../style.css">
        <title>Student Sign Up</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Student Sign Up</h1>
        <h3>Please fill in all the fields in the form below to complete 
            your registration:</h3>
        <form method="post" 
        action="">

            <!--Student Information-->
        <label for="studentName">Name: </label>
        <input type="text" name="studentName" id="studentName"
        <?php if(isset($_SESSION['studentName'])) { 
            echo 'value="' . $_SESSION['studentName'] . '"'; 
            } ?> required><br>

        <label for="studentSurname">Surname: </label>
        <input type="text" name="studentSurname" id="studentSurname"
        <?php if(isset($_SESSION['studentSurname'])) { 
        echo 'value="' . $_SESSION['studentSurname'] . '"'; 
        } ?> required><br>

        <label for="studentEmail">Email: </label>
        <input type="email" name="studentEmail" id="studentEmail"
        <?php if(isset($_SESSION['studentEmail'])) { 
        echo 'value="' . $_SESSION['studentEmail'] . '"'; 
        } ?> required><br>

        <label for="studentPhoneNumber">Phone Number: </label>
        <input type="text" name="studentPhoneNumber" id="studentPhoneNumber"
        <?php if(isset($_SESSION['studentPhoneNumber'])) { 
        echo 'value="' . $_SESSION['studentPhoneNumber'] . '"'; 
        } ?> required><br>


        <label for="studentGender">Gender: </label>
        <select name="studentGender" id="studentGender" required>

        <option value="Male" <?php if(isset($_SESSION['studentGender']) 
        && $_SESSION['studentGender'] == 'Male') { echo 'selected'; } ?>
        >Male</option>

        <option value="Female" <?php if(isset($_SESSION['studentGender']) 
        && $_SESSION['studentGender'] == 'Female') { echo 'selected'; } ?>
        >Female</option>

        <option value="Other" <?php if(isset($_SESSION['studentGender']) 
        && $_SESSION['studentGender'] == 'Other') { echo 'selected'; } ?>
        >Other</option>
        </select><br>


        <label for="studentAge">Age: </label>
        <input type="number" name="studentAge" id="studentAge"
        <?php if(isset($_SESSION['studentAge'])) { 
        echo 'value="' . $_SESSION['studentAge'] . '"'; 
        } ?> required><br>


        <label for="education">Current Education level: </label>
        <select name="education" id="education" required>
        
        <option value="High School" <?php if(isset($_SESSION['education']) 
        && $_SESSION['education'] == 'High School') { echo 'selected'; } ?>
        >I am a high school student.</option>
        
        <option value="Bachelor" <?php if(isset($_SESSION['education']) && 
        $_SESSION['education'] == 'Bachelor') { echo 'selected'; } ?>
        >I am a bachelor student.</option>
        
        <option value="Master" <?php if(isset($_SESSION['education']) && 
        $_SESSION['education'] == 'Master') { echo 'selected'; } ?>
        >I am a master student.</option>
        
        <option value="PHD" <?php if(isset($_SESSION['education']) && 
        $_SESSION['education'] == 'PHD') { echo 'selected'; } ?>
        >I am a phd student</option>
        </select><br>


        <label for="studentAddress">Please write your address below:</label><br>

        <textarea name="studentAddress" id="studentAddress" cols="30" 
        rows="10" required>
        <?php if(isset($_SESSION['studentAddress'])) { 
        echo $_SESSION['studentAddress']; 
        } ?>
        </textarea><br>

        <p>Please enter a password that contains at least 8 characters 
        and a combination of letters and numbers:</p>

        <label for="studentPassword">Password: </label>
        <input type="password" name="studentPassword" id="studentPassword" 
        required

        <?php if(isset($_SESSION['studentPassword'])) { 
        echo 'value="' . $_SESSION['studentPassword'] . '"'; 
        } ?>><br>

        <p>Please Confirm Your Password:</p>

        <label for="studentPasswordCopy">Password: </label>
        <input type="password" name="studentPasswordCopy" 
        id="studentPasswordCopy" required
        <?php if(isset($_SESSION['studentPasswordCopy'])) { 
        echo 'value="' . $_SESSION['studentPasswordCopy'] . '"'; 
        } ?>><br>

        <!-- Guardian Information-->
        <p>We need a person (like your father for example) that you can 
        enter as your guardian.</p>
        <label for="guardianName">Name: </label>
        <input type="text" name="guardianName" id="guardianName" required

        <?php if(isset($_SESSION['guardianName'])) { 
        echo 'value="' . $_SESSION['guardianName'] . '"'; 
        } ?>><br>

        <label for="guardianSurname">Surname: </label>
        <input type="text" name="guardianSurname" id="guardianSurname" 
        required

        <?php if(isset($_SESSION['guardianSurname'])) { 
        echo 'value="' . $_SESSION['guardianSurname'] . '"'; 
        } ?>><br>

        <label for="guardianAge">Age: </label>
        <input type="number" name="guardianAge" id="guardianAge" required

        <?php if(isset($_SESSION['guardianAge'])) { 
        echo 'value="' . $_SESSION['guardianAge'] . '"'; 
        } ?>><br>

        <label for="guardianPhoneNumber">Phone Number: </label>
        <input type="text" name="guardianPhoneNumber" 
        id="guardianPhoneNumber" required

        <?php if(isset($_SESSION['guardianPhoneNumber'])) { 
        echo 'value="' . $_SESSION['guardianPhoneNumber'] . '"'; 
        } ?>><br>

        <label for="guardianEmail">Email: </label>
        <input type="email" name="guardianEmail" id="guardianEmail" 
        required

        <?php if(isset($_SESSION['guardianEmail'])) { 
        echo 'value="' . $_SESSION['guardianEmail'] . '"'; 
        } ?>><br>


        <p>What is your relationship with this person?</p>
        <label for="guardianRelation">This person is my: </label>
        <select name="guardianRelation" id="guardianRelation" required>

        <option value="Brother" 
        <?php if(isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Brother') { echo 'selected'; } ?>
        >Brother</option>
        
        <option value="Sister" 
        <?php if(isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Sister') { echo 'selected'; } ?>
        >Sister</option>
        
        <option value="Mother" 
        <?php if(isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Mother') { echo 'selected'; } ?>
        >Mother</option>
        
        <option value="Father" 
        <?php if(isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Father') { echo 'selected'; } ?>
        >Father</option>
        
        <option value="Other Relationship" 
        <?php if(isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Other Relationship') 
        { echo 'selected'; } ?>>Other Relationship</option>
        </select><br>

            
        <p>Click the button below to continue and book a room:</p>
        <input type="submit" name="submit" value="Finish Registration">
        </form>

        <a href="../prices.php">Room Prices</a>
        <a href="../home.php">Cancel</a>
    </body>
</html>