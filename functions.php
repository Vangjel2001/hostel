<?php 
    require_once "pdo.php";

    function displaySuccessMessage()
    {
        //if there is a success message stored in the superglobal
        //array $_SESSION, print it on the browser with green color 
        //writing
        if( isset($_SESSION['success']) ) {
            echo'<p style="color:green">'.$_SESSION['success']."</p>\n";
            //delete the array key success from $_SESSION
            unset($_SESSION['success']);
        }        
    }

    function displayErrorMessage()
    {
        //if there is an error message stored in the superglobal
        //array $_SESSION, print it on the browser with red color 
        //writing
        if( isset($_SESSION['error']) ) {
            echo'<p style="color:red">'.$_SESSION['error']."</p>\n";
            //delete the array key error from $_SESSION
            unset($_SESSION['error']);
        }        
    }

    //check if the student name is in the database
    function studentNameFound($studentName, $pdo)
    {
        $sql="SELECT * 
        FROM student 
        WHERE studentName=:zip;";

        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":zip", $studentName);
        $stmt->execute();

        //check if there was a studentName with the provided parameter 
        //value in the student table
        if($stmt->rowCount()==0)
        return false;

        return true;

    }

    //check if the student surname is in the database
    function studentSurnameFound($studentSurname, $pdo)
    {
        $sql="SELECT * 
        FROM student 
        WHERE studentSurname=:zip;";

        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":zip", $studentSurname);
        $stmt->execute();

        //check if there was a studentSurname with the provided parameter 
        //value in the student table
        if($stmt->rowCount()==0)
        return false;

        return true;

    }

    //check if the student email is in the database
    function studentEmailFound($studentEmail, $pdo)
    {
        $sql="SELECT * 
        FROM student 
        WHERE studentEmail=:zip;";

        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":zip", $studentEmail);
        $stmt->execute();

        //check if there was a studentEmail with the provided parameter 
        //value in the student table
        if($stmt->rowCount()==0)
        return false;

        return true;

    }

    function getStudentPassword($studentEmail, $pdo)
    {
        $sql="SELECT studentPassword 
        FROM student 
        WHERE studentEmail=:zip;";

        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":zip", $studentEmail);
        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return $row['studentPassword'];
    }

    /*prepare to fill form input fields with empty values if the form 
    has not been submitted yet*/
    function initializeStudentInput()
    {
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

        return $student;
    }

    //prepare to fill form fields with the submitted values
    function returnStudentInput()
    {
        $student['studentName']=htmlentities($_POST['studentName']);
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

        return $student;
    }

    //insert student in the student table
    function insertRowIntoStudent($guardianID, $pdo)
    {
        $hashed_password = 
        password_hash($_POST['studentPassword'], PASSWORD_BCRYPT);

        $sql="INSERT INTO student(studentName, studentSurname, 
        studentEmail, studentPhoneNumber, studentGender, 
        guardianRelation, studentAge, education, studentAddress, 
        studentPassword, guardianID) 
        VALUES(:studentName, :studentSurname, 
        :studentEmail, :studentPhoneNumber, :studentGender, 
        :guardianRelation, :studentAge, :education, :studentAddress, 
        :studentPassword, :guardianID)";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(
            ":studentName" => $_POST['studentName'],
            ":studentSurname" => $_POST['studentSurname'],
            ":studentEmail" => $_POST['studentEmail'],
            ":studentPhoneNumber" => $_POST['studentPhoneNumber'],
            ":studentGender" => $_POST['studentGender'],
            ":guardianRelation" => $_POST['guardianRelation'],
            ":studentAge" => $_POST['studentAge'],
            ":education" => $_POST['education'],
            ":studentAddress" => $_POST['studentAddress'],
            ":studentPassword" => $hashed_password,
            ":guardianID" => $guardianID
        ));
    }

    function updateStudentRow($pdo, $studentID, $guardianID)
    {

        $sql="UPDATE student 
        SET studentName= :studentName, 
        studentSurname= :studentSurname, studentEmail= :studentEmail, 
        studentPhoneNumber= :studentPhoneNumber, studentGender= 
        :studentGender, guardianRelation= :guardianRelation, 
        studentAge= :studentAge, education= :education, 
        studentAddress= :studentAddress, guardianID= :guardianID 
        WHERE studentID=$studentID;";

        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(
            ":studentName" => $_POST['studentName'],
            ":studentSurname" => $_POST['studentSurname'],
            ":studentEmail" => $_SESSION['studentEmail'],
            ":studentPhoneNumber" => $_POST['studentPhoneNumber'],
            ":studentGender" => $_POST['studentGender'],
            ":guardianRelation" => $_POST['guardianRelation'],
            ":studentAge" => $_POST['studentAge'],
            ":education" => $_POST['education'],
            ":studentAddress" => $_POST['studentAddress'],
            ":guardianID" => $guardianID
        ));
    }

    /*return student information for the student with the given 
    studentEmail*/
    function returnStudentRow($studentEmail, $pdo)
    {
        $sql="SELECT * 
        FROM student 
        WHERE studentEmail=:zip;";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":zip", $studentEmail);
        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    /*prepare to fill form input fields with empty values if the form 
    has not been submitted yet*/
    function initializeGuardianInput()
    {
        $guardian['guardianName']="";
        $guardian['guardianSurname']="";
        $guardian['guardianPhoneNumber']="";
        $guardian['guardianEmail']="";
        $guardian['guardianAge']="";

        return $guardian;
    }

    //prepare to fill form fields with the submitted values
    function returnGuardianInput()
    {
        $guardian['guardianName']=htmlentities($_POST['guardianName']);
        $guardian['guardianSurname']=
        htmlentities($_POST['guardianSurname']);
        $guardian['guardianPhoneNumber']=
        htmlentities($_POST['guardianPhoneNumber']);
        $guardian['guardianEmail']=htmlentities($_POST['guardianEmail']);
        $guardian['guardianAge']=htmlentities($_POST['guardianAge']);
        echo 'Guardian Input returned.';

        return $guardian;
    }

    //insert a guardian in the guardian table
    function insertRowIntoGuardian($pdo)
    {
        $sql="INSERT INTO guardian(guardianName, guardianSurname, 
        guardianPhoneNumber, guardianEmail, guardianAge) 
        VALUES(:guardianName, :guardianSurname, :guardianPhoneNumber, 
        :guardianEmail, :guardianAge)";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(
            ":guardianName" => $_POST['guardianName'],
            ":guardianSurname" => $_POST['guardianSurname'],
            ":guardianPhoneNumber" => $_POST['guardianPhoneNumber'],
            ":guardianEmail" => $_POST['guardianEmail'],
            ":guardianAge" => $_POST['guardianAge']
        ));
    }

    function updateGuardianRow($pdo, $guardianID)
    {
        $sql="UPDATE guardian 
        SET guardianName= :guardianName, 
        guardianSurname= :guardianSurname, guardianPhoneNumber= 
        :guardianPhoneNumber, guardianEmail= :guardianEmail, 
        guardianAge= :guardianAge 
        WHERE guardianEmail= :guardianEmail;";

        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(
            ":guardianName" => $_POST['guardianName'],
            ":guardianSurname" => $_POST['guardianSurname'],
            ":guardianPhoneNumber" => $_POST['guardianPhoneNumber'],
            ":guardianEmail" => $_POST['guardianEmail'],
            ":guardianAge" => $_POST['guardianAge']
        ));
    }

    /*return guardian information for the guardian with the given 
    guardianEmail*/
    function returnGuardianRow($guardianEmail, $pdo)
    {
        $sql="SELECT * 
        FROM guardian 
        WHERE guardianEmail=:zip";
        
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":zip", $guardianEmail);
        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }

    function getStudentAndGuardianRow($studentID, $pdo)
    {
        $sql="SELECT s.studentName, s.studentSurname, s.studentEmail, 
        s.studentPhoneNumber, s.studentGender, s.guardianRelation, 
        s.studentAge, s.education, s.studentAddress, 
        g.guardianName, g.guardianSurname, g.guardianPhoneNumber, 
        g.guardianEmail, g.guardianAge 
        FROM student s, guardian g
        WHERE s.studentID=$studentID AND s.guardianID=g.guardianID;";

        $stmt=$pdo->query($sql);

        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    function getGuardianID($studentID, $pdo)
    {
        $sql="SELECT g.guardianID
        FROM student s, guardian g 
        WHERE s.studentID= $studentID AND s.guardianID=g.guardianID;";

        $stmt=$pdo->query($sql);

        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        return $row['guardianID'];
    }

    function hashedPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    
    function getBookingsAndRoomsInfo($studentID, $pdo)
    {
        $sql="SELECT b.roomNumber, b.duration, b.startDate, b.endDate, 
        b.totalFee, b.foodStatus, r.capacity, r.numberOfStudents 
        FROM booking b, room r
        WHERE b.studentID=$studentID AND r.roomNumber=b.roomNumber;";

        $stmt=$pdo->query($sql);

        return $stmt;
    }

    function getRoomInfo($roomNumber, $pdo)
    {
        $sql="SELECT *
        FROM room 
        WHERE roomNumber=$roomNumber;";

        $stmt=$pdo->query($sql);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    function getFreeRoomNumbers($pdo)
    {
        $sql="SELECT roomNumber 
        FROM room 
        WHERE numberOfStudents=0;";

        $stmt=$pdo->query($sql);

        return $stmt;
    }

    function getDateDifferenceInDays($startDate, $endDate)
    {
        $differenceSeconds=strtotime($endDate)-strtotime($startDate);
        
        $differenceDays=$differenceSeconds/(24*60*60);

        return $differenceDays;
    }

    function getMeals()
    {
        if($_POST['foodStatus']=="Just Breakfast" || 
        $_POST['foodStatus']=="Just Lunch" || $_POST['foodStatus']==
        "Just Dinner")
        {
            $meals=1;
        }
        else if($_POST['foodStatus']=="Breakfast and Lunch" || 
        $_POST['foodStatus']=="Breakfast and Dinner" || 
        $_POST['foodStatus']=="Lunch and Dinner")
        {
            $meals=2;
        }
        else if($_POST['foodStatus']=="Breakfast, Lunch and Dinner")
        {
            $meals=3;
        }
        else
        {
            $meals=0;
        }

        return $meals;
    }

    function deleteBookingRow($pdo)
    {
        $studentID=$_POST['studentID'];
        $roomNumber=$_POST['roomNumber'];

        $sql="DELETE FROM booking 
        WHERE studentID=$studentID AND roomNumber=$roomNumber;";

        $pdo->query($sql);
    }

    function missingGetParameters($filename)
    {
        if(!isset($_GET['studentID']) || !isset($_GET['roomNumber']))
        {
            $_SESSION['error']="Please press the appropriate button to 
            come to $filename.";
            
            return true;
        }
        return false;
    }

    function wrongGetParameters($filename, $pdo)
    {
        $studentID=$_GET['studentID'];
        $roomNumber=$_GET['roomNumber'];

        if($studentID!=$_SESSION['studentID'])
        {
            $_SESSION['error']="Please do not try to access reservations 
            done by other students.";
            return true;
        }

        $sql="SELECT * 
        FROM booking 
        WHERE studentID=:studentID AND roomNumber=:roomNumber;";

        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':studentID', $studentID);
        $stmt->bindParam(':roomNumber', $roomNumber);
        $stmt->execute();

        if($stmt->rowCount()==0)
        {
            $_SESSION['error']="Please do not put inappropriate values 
            on studentID and roomNumber in the URL.";
            return true;
        }
        

        return false;
    }

    function getBookingInfo($pdo)
    {
        $studentID=$_GET['studentID'];
        $roomNumber=$_GET['roomNumber'];

        $sql="SELECT * 
        FROM booking 
        WHERE studentID=:studentID AND roomNumber=:roomNumber;";

        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':studentID', $studentID);
        $stmt->bindParam(':roomNumber', $roomNumber);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }



?>