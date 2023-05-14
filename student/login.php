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

    //Preparing to store values for the form fields
    $studentEmail="";
    $studentPassword="";

    //check if the form has been submitted
    if(isset($_POST['submit']))
    {
        //Preparing to store values for the form fields
        $studentEmail=htmlentities($_POST['studentEmail']);
        $studentPassword=htmlentities($_POST['studentPassword']);

        //check if the email submitted exists in the database
        if(studentEmailFound($_POST['studentEmail'], $pdo)==false)
        {
            $_SESSION['error']="The email entered was not found.";
            header("Location: login.php");
            return;
        }

        //get the hashed password of the student
        $DatabaseStudentPassword=
        getStudentPassword($_POST['studentEmail'], $pdo);

        //check if the password entered is correct
        if(password_verify($_POST['studentPassword'], 
        $DatabaseStudentPassword))
        {
           /*save the student email and password inside $_SESSION for 
        later use*/
        $_SESSION['studentEmail']=$_POST['studentEmail'];
        $_SESSION['studentPassword']=$_POST['studentPassword'];

        //get the auto-generated studentID from the student table
        $row=returnStudentRow($_POST['studentEmail'], $pdo);
        //store it inside $_SESSION for later use
        $_SESSION['studentID']=$row['studentID'];


        /*save the guardianID inside $_SESSION for 
        later use*/
        
        $_SESSION['guardianID']= 
        getGuardianID($_SESSION['studentID'], $pdo);

        

        /*Write a success message and send the logged in student 
        to the student home page*/
        $_SESSION['success']="Login was successful!";
        header("Location: home.php");
        return;
        }else{
            //Tell the user that the password entered is not correct
            $_SESSION['error']="The password is not correct.";
            header("Location: login.php");
            return;
        }

       
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" 
        href="file:///C:/xampp/htdocs/hostel/style.css">
        <title>Student Log In</title>
        <style>

        </style>
    </head>
        
    <body>
        <h1>Student Log In</h1>
        <h3>Please provide the following information:</h3>

        <form method="post" action="">
            <label for="studentEmail">Email: </label>
            <input type="email" name="studentEmail" id="studentEmail" 
            value="<?php echo $studentEmail; ?>" required><br>

            <label for="studentPassword">Password: </label>
            <input type="password" name="studentPassword" 
            id="studentPassword" 
            value="<?php echo $studentPassword; ?>" required><br>

            <input type="submit" name="submit" value="Log In">
        </form>

        <a href="../home.php">Cancel</a>

    </body>
</html>