<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" 
        href="file:///C:/xampp/htdocs/hostel/style.css">
        <title>Add A Room</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Add A Room</h1>
        <form method="post" action="">
            <label for="roomNumber">Room Number: </label>
            <input type="number" name="roomNumber" id="roomNumber" 
            required><br>

            <label for="mealFee">Meal Fee: </label>
            <input type="number" name="mealFee" id="mealFee" required>
            <br>

            <label for="regularFee">Base Fee: </label>
            <input type="number" name="regularFee" id="regularFee" 
            required><br>

            <label for="capacity">Capacity: </label>
            <input type="number" name="capacity" id="capacity" required>
            <br>

            <label for="numberOfStudents">Number Of Students Occupying 
                The Room</label>
            <input type="number" name="numberOfStudents" 
            id="numberOfStudents" required><br>

            <input type="submit" name="submit" value="Add Room">
        </form>

        <a href="view.php">Cancel</a>
    </body>
</html>