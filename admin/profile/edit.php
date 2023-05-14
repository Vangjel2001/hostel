<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" 
        href="file:///C:/xampp/htdocs/hostel/style.css">
        <title>Administrator Log In Information Update</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Administrator Log In Information Update</h1>
        <h3>Enter the new log in credentials below:</h3>

        <form method="post" action="">
            <label for="adminName">Administrator Name: </label>
            <input type="text" name="adminName" 
            id="adminName" required><br>

            <p>Please enter a password that contains at least 8 characters 
                and a combination of letters and numbers:</p>
            <label for="adminPassword">Password: </label>
            <input type="password" name="adminPassword" 
            id="adminPassword" required><br>

            <p>Please Confirm Your Password:</p>
            <label for="adminPasswordCopy">Password: </label>
            <input type="password" name="adminPasswordCopy" 
            id="adminPasswordCopy" required><br>

            <input type="submit" name="submit" value="Update Profile">
        </form>

        <a href="view.php">Cancel</a>
    </body>
</html>