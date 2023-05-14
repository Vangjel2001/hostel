<?php 
    /*Problems:
    -The submitted form values are not displayed again when the user
    is redirected to this same page. The user has to fill all the 
    form fields again.
    */


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

    /*prepare to fill form input fields with empty values if the form 
    has not been submitted yet*/
    $guardian=Array();
    $student=Array();
    $student=initializeStudentInput();
    $guardian=initializeGuardianInput();
    
    /*
    $guardian['guardianName']="";
        $guardian['guardianSurname']="";
        $guardian['guardianPhoneNumber']="";
        $guardian['guardianEmail']="";
        $guardian['guardianAge']="";

        $student['studentName']="";
        $student['studentSurname']="";
        $student['studentEmail']="";
        $student['studentPhoneNumber']="";
        $student['studentGender']="";
        $student['guardianRelation']="";
        $student['studentAge']="";
        $student['education']="";
        $student['studentAddress']="";
        $student['studentPassword']="";

        print_r($guardian);
        print_r($student);*/

    //check if the form has been submitted
    if(isset($_POST['submit']))
    {
        //prepare to fill form fields with the submitted values
        $student=returnStudentInput();
        //echo 'ZZZZZZZZZZZZZZZZ'.$student['studentName'];
        $guardian=returnGuardianInput();

       /* $student['studentName']=htmlentities($_POST['studentName']);
        $student['studentSurname']=htmlentities($_POST['studentSurname']);
        $student['studentEmail']=htmlentities($_POST['studentEmail']);
        $student['studentPhoneNumber']=
        htmlentities($_POST['studentPhoneNumber']);
        $student['studentGender']=htmlentities($_POST['studentGender']);
        $student['guardianRelation']=
        htmlentities($_POST['guardianRelation']);
        $student['studentAge']=htmlentities($_POST['studentAge']);
        $student['education']=htmlentities($_POST['education']);
        $student['studentAddress']=htmlentities($_POST['studentAddress']);
        $student['studentPassword']=
        htmlentities($_POST['studentPassword']);
        echo 'Student input returned.'.'<br>';

        $guardian['guardianName']=htmlentities($_POST['guardianName']);
        $guardian['guardianSurname']=
        htmlentities($_POST['guardianSurname']);
        $guardian['guardianPhoneNumber']=
        htmlentities($_POST['guardianPhoneNumber']);
        $guardian['guardianEmail']=htmlentities($_POST['guardianEmail']);
        $guardian['guardianAge']=htmlentities($_POST['guardianAge']);
        echo 'Guardian Input returned.';
        print_r($guardian);
        print_r($student);*/

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
        <link rel="stylesheet" 
        href="file:///C:/xampp/htdocs/hostel/style.css">
        <title>Student Sign Up</title>
        <style>

        </style>
    </head>

    <body>
         <?php echo "Name: ".$student['studentName'].'<br>'; ?>
        <h1>Student Sign Up</h1>
        <h3>Please fill in all the fields in the form below to complete 
            your registration:</h3>
        <form method="post">

            <!--Student Information-->
            <label for="studentName">Name: </label>
            <input type="text" name="studentName" id="studentName" 
            value="<?=$student['studentName']?>" required>
            <br>
            
            <label for="studentSurname">Surname: </label>
            <input type="text" name="studentSurname" id="studentSurname" 
            value="<?=$student['studentSurname']?>" required><br>

            <label for="studentEmail">Email: </label>
            <input type="email" name="studentEmail" id="studentEmail" 
            value="<?=$student['studentEmail']?>" required>
            <br>

            <label for="studentPhoneNumber">Phone Number: </label>
            <input type="text" name="studentPhoneNumber" 
            id="studentPhoneNumber" 
            value="<?=$student['studentPhoneNumber']?>" required><br>
            

            <label for="studentGender">Gender: </label>
            <select name="studentGender" id="studentGender" 
            value="<?=$student['studentGender']?>" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select><br>

            <label for="studentAge">Age: </label>
            <input type="number" name="studentAge" id="studentAge" 
            value="<?=$student['studentAge']?>" required><br>

            <label for="education">Current Education level: </label>
            <select name="education" id="education" 
            value="<?=$student['education']?>" required>
                <option value="High School">
                    I am a high school student.</option>
                <option value="Bachelor">
                    I am a bachelor student.</option>
                <option value="Master">I am a master student.</option>
                <option value="PHD">I am a phd student</option>
            </select><br>

            <label for="studentAddress">
                Please write your address below:</label><br>
            <textarea name="studentAddress" id="studentAddress" 
            cols="30" rows="10" 
            value="<?=$student['studentAddress']?>" required></textarea><br>

            <p>Please enter a password that contains at least 8 characters 
                and a combination of letters and numbers:</p>
            <label for="studentPassword">Password: </label>
            <input type="password" name="studentPassword" 
            id="studentPassword" required><br>

            <p>Please Confirm Your Password:</p>
            <label for="studentPasswordCopy">Password: </label>
            <input type="password" name="studentPasswordCopy" 
            id="studentPasswordCopy" required><br>


            <!-- Guardian Information-->
            <p>We need a person (like your father for example) that
                 you can enter as your guardian.</p>

            <label for="guardianName">Name: </label>
            <input type="text" name="guardianName" id="guardianName" 
            value="<?=$guardian['guardianName']?>" required><br>

            <label for="guardianSurname">Surname: </label>
            <input type="text" name="guardianSurname" 
            id="guardianSurname" value="<?=$guardian['guardianSurname']?>" 
            required><br>

            <label for="guardianAge">Age: </label>
            <input type="number" name="guardianAge" id="guardianAge" 
            value="<?=$guardian['guardianAge']?>" required><br>

            <label for="guardianPhoneNumber">Phone Number: </label>
            <input type="text" name="guardianPhoneNumber" 
            id="guardianPhoneNumber" 
            value="<?=$guardian['guardianPhoneNumber']?>" required><br>

            <label for="guardianEmail">Email: </label>
            <input type="email" name="guardianEmail" id="guardianEmail" 
            value="<?=$guardian['guardianEmail']?>" required><br>

            <p>What is your relationship with this person?</p>
            <label for="guardianRelation">This person is my: </label>
            <select name="guardianRelation" id="guardianRelation" 
            value="<?=$student['guardianRelation']?>" required>
                <option value="Brother">Brother</option>
                <option value="Sister">Sister</option>
                <option value="Mother">Mother</option>
                <option value="Father">Father</option>
                <option value="Other Relationship">Other Relationship
                </option>    
            </select><br>

            <?php 
                /*print_r($guardian);
                print_r($student);*/
            ?>

            <p>Click the button below to continue and book a room:</p>
            <input type="submit" name="submit" value="Finish Registration">
        </form>

        <a href="../home.php">Cancel</a>
    </body>
</html>