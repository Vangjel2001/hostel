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


    //get the information for all students and guardians
    $sql="SELECT s.studentID, s.studentName, s.studentSurname, 
    s.studentEmail, s.studentPhoneNumber, s.studentGender, 
    s.guardianRelation, s.studentAge, s.education, 
    s.studentAddress, s.guardianID, g.guardianName, g.guardianSurname, 
    g.guardianPhoneNumber, g.guardianEmail, g.guardianAge 
    FROM student s, guardian g 
    WHERE s.guardianID=g.guardianID;";
    $stmt=$pdo->query($sql);

?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="../../style.css">
        <title>Students And Student Advocates</title>
    </head>

    <body>
        <div class="container">
        <h1>Students And Student Advocates</h1>
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e8/Hostel_Dormitory.jpg">
        <h3>Below is the information for each student and advocate:</h3>

        <table border="1">
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Student Surname</th>
                <th>Student Email</th>
                <th>Student Phone Number</th>
                <th>Student Gender</th>
                <th>Student Age</th>
                <th>Student Education</th>
                <th>Student Address</th>
                <th>Advocate Relationship</th>
                <th>Advocate ID</th>
                <th>Advocate Name</th>
                <th>Advocate Surname</th>
                <th>Advocate Age</th>
                <th>Advocate Phone Number</th>
                <th>Advocate Email</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>

            <?php 
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    echo '<tr><td>';
                    echo $row['studentID'].'</td><td>';
                    echo htmlentities($row['studentName']).'</td><td>';
                    echo htmlentities($row['studentSurname']).'</td><td>';
                    echo htmlentities($row['studentEmail']).'</td><td>';
                    echo htmlentities($row['studentPhoneNumber']).
                    '</td><td>';
                    echo htmlentities($row['studentGender']).'</td><td>';
                    echo htmlentities($row['studentAge']).'</td><td>';
                    echo htmlentities($row['education']).'</td><td>';
                    echo htmlentities($row['studentAddress']).'</td><td>';
                    echo htmlentities($row['guardianRelation']).'</td><td>';
                    echo htmlentities($row['guardianID']).'</td><td>';
                    echo htmlentities($row['guardianName']).'</td><td>';
                    echo htmlentities($row['guardianSurname']).'</td><td>';
                    echo htmlentities($row['guardianAge']).'</td><td>';
                    echo htmlentities($row['guardianPhoneNumber']).
                    '</td><td>';
                    echo htmlentities($row['guardianEmail']).'</td><td>';

                    $studentID=$row['studentID'];
                    echo '<a href="edit.php?studentID='.$studentID.'">
                    Edit</a></td><td>';
                    echo '<a href="delete.php?studentID='.$studentID.'">
                    Delete</a></td></tr>';
                }
            ?>
        </table>

        <a href="add.php">Add a student and advocate</a>
        <a href="../home.php">Go Back</a>
        <a href="../../logout.php">Log Out</a>
        </div>
    </body>
</html>