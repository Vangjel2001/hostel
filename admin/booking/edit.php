<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" 
        href="file:///C:/xampp/htdocs/hostel/style.css">
        <title>Edit Reservation</title>
        <style>

        </style>
    </head>

    <body>
        <h1>Edit Reservation</h1>
        <form method="post" action="">
            <label for="studentEmail">Student Email: </label>
            <input type="email" name="studentEmail" id="studentEmail" 
            required><br>

            <label for="roomNumber">Room Number: </label>
            <select name="roomNumber" id="roomNumber" required>

            </select><br>

            <label for="startDate">Reservation Start Date: </label>
            <input type="date" name="startDate" id="startDate" 
            required><br>

            <label for="endDate">Reservation End Date: </label>
            <input type="date" name="endDate" id="endDate" required>
            <br>

            <p>Do you want to have breakfast, lunch or dinner included?</p>
            <label for="foodStatus">Choose one of the options below: </label>
            <select name="foodStatus" id="foodStatus" required>
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
            </select><br>

            <input type="submit" name="submit" value="Update Reservation">
        </form>

        <a href="view.php">Cancel</a>
    </body>


</html>