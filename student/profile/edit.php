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



        //check if the guardian is underage
        if($_POST['guardianAge']<18)
        {
            $_SESSION['error']="Your advocate should be 18 years old 
            or older. Try entering another advocate.";
            header("Location: edit.php");
            return;
        }
        //check if the guardian has the same phone number as the student
        if($_POST['guardianPhoneNumber']==$_POST['studentPhoneNumber'])
        {
            $_SESSION['error']="Your advocate should have a different 
            phone number from yours.";
            header("Location: edit.php");
            return;
        }

         //check if the password has not been confirmed correctly
         if($_POST['studentPassword']!==$_POST['studentPasswordCopy'])
         {   
             $_SESSION['error']="You did not confirm your entered password 
             correctly. Please try entering your password correctly 
             twice.";
             header("Location: edit.php");
             return;
         }

         
        //update guardian in the guardian table
        updateGuardianRow($pdo, $_SESSION['guardianID']);
        

        //update student in the student table
        updateStudentRow($pdo, $_SESSION['studentID']/*, 
        $_SESSION['guardianID']*/);


        /*Write a success message and send the signed up student 
        to the student home page*/
        $_SESSION['success']="Your profile was updated successfully!";
        header("Location: view.php");
        return;
    }

    //get the student and guardian information
    $row=getStudentAndGuardianRow($_SESSION['studentID'], $pdo);

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
        <div class="container">
        <h1>Student Profile</h1>
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Hostel_Dormitory.jpg">
        <form method="post">

        <!-- Student Information -->
        <label for="studentName">Name: </label>
        <input type="text" name="studentName" id="studentName" 
        value="<?php echo isset($_SESSION['studentName']) ? 
        $_SESSION['studentName'] : $row['studentName']; ?>" required><br>

        <label for="studentSurname">Surname: </label>
        <input type="text" name="studentSurname" id="studentSurname" 
        value="<?php echo isset($_SESSION['studentSurname']) ? 
        $_SESSION['studentSurname'] : $row['studentSurname']; ?>" 
        required><br>

        <label for="studentEmail">Email: </label>
        <input type="email" name="studentEmail" id="studentEmail" 
        value="<?php echo isset($_SESSION['studentEmail']) ? 
        $_SESSION['studentEmail'] : $row['studentEmail']; ?>" required><br>

        <label for="studentPhoneNumber">Phone Number: </label>
        <input type="text" name="studentPhoneNumber" 
        id="studentPhoneNumber" value="<?php echo 
        isset($_SESSION['studentPhoneNumber']) ? 
        $_SESSION['studentPhoneNumber'] : $row['studentPhoneNumber']; ?>" 
        required><br>

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
        value="<?php echo isset($_SESSION['studentAge']) ? 
        $_SESSION['studentAge'] : $row['studentAge']; ?>" required><br>

        <label for="education">Current Education level: </label>
        <select name="education" id="education" required>

        <option value="High School" 
        <?php echo (isset($_SESSION['education']) && 
        $_SESSION['education'] == 'High School') ? 'selected' : 
        ($row['education'] == 'High School' ? 'selected' : ''); ?>
        >I am a high school student.</option>
        
        <option value="Bachelor" 
        <?php echo (isset($_SESSION['education']) && 
        $_SESSION['education'] == 'Bachelor') ? 'selected' : 
        ($row['education'] == 'Bachelor' ? 'selected' : ''); ?>
        >I am a bachelor student.</option>
        
        <option value="Master" 
        <?php echo (isset($_SESSION['education']) && 
        $_SESSION['education'] == 'Master') ? 'selected' : 
        ($row['education'] == 'Master' ? 'selected' : ''); ?>
        >I am a master student.</option>
        
        <option value="PHD" 
        <?php echo (isset($_SESSION['education']) && 
        $_SESSION['education'] == 'PHD') ? 'selected' : 
        ($row['education'] == 'PHD' ? 'selected' : ''); ?>
        >I am a PhD student.</option>
    
        </select><br>

        <label for="studentAddress">Please write your address below:
        </label><br>
        <textarea name="studentAddress" id="studentAddress" cols="30" 
        rows="10" required>
        <?php echo isset($_SESSION['studentAddress']) ? 
        $_SESSION['studentAddress'] : $row['studentAddress']; ?>
        </textarea><br>

        
        <!-- Guardian Information -->
        <p>We need a person (like your father for example) that you can 
            enter as your advocate.</p>

        <label for="guardianName">Advocate Name: </label>
        <input type="text" name="guardianName" id="guardianName" required
        value="<?php echo isset($_SESSION['guardianName']) ? 
        $_SESSION['guardianName'] : $row['guardianName']; ?>"><br>

        <label for="guardianSurname">Advocate Surname: </label>
        <input type="text" name="guardianSurname" id="guardianSurname" 
        required value="<?php echo isset($_SESSION['guardianSurname']) ? 
        $_SESSION['guardianSurname'] : $row['guardianSurname']; ?>"><br>

        <label for="guardianAge">Advocate Age: </label>
        <input type="number" name="guardianAge" id="guardianAge" required
        value="<?php echo isset($_SESSION['guardianAge']) ? 
        $_SESSION['guardianAge'] : $row['guardianAge']; ?>"><br>

        <label for="guardianPhoneNumber">Advocate Phone Number: </label>
        <input type="text" name="guardianPhoneNumber" 
        id="guardianPhoneNumber" required
        value="<?php echo isset($_SESSION['guardianPhoneNumber']) ? 
        $_SESSION['guardianPhoneNumber'] : $row['guardianPhoneNumber']; 
        ?>"><br>

        <label for="guardianEmail">Advocate Email: </label>
        <input type="email" name="guardianEmail" id="guardianEmail" 
        required value="<?php echo isset($_SESSION['guardianEmail']) ? 
        $_SESSION['guardianEmail'] : $row['guardianEmail']; ?>"><br>

        <p>What is your relationship with this person?</p>

        <label for="guardianRelation">This person is my: </label>
        <select name="guardianRelation" id="guardianRelation" required>

        <option value="Brother" <?php echo 
        (isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Brother') ? 'selected' : 
        ($row['guardianRelation'] == 'Brother' ? 'selected' : ''); ?>
        >Brother</option>
        
        <option value="Sister" 
        <?php echo (isset($_SESSION['guardianRelation']) && 
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
        
        <option value="Other Relationship" 
        <?php echo (isset($_SESSION['guardianRelation']) && 
        $_SESSION['guardianRelation'] == 'Other Relationship') ? 
        'selected' : ($row['guardianRelation'] == 'Other Relationship' ? 
        'selected' : ''); ?>>Other Relationship</option>

        </select><br>

        <input type="submit" name="submit" value="Update Profile">
        </form>

        <a href="../../prices.php">Room Prices</a>
        <a href="view.php">Cancel</a>
        <a href="../../logout.php">Log Out</a>
        </div>
    </body>
</html>