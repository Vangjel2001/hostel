<?php 

    /*require the script from the database connection file
    and the file containing user-defined functions*/
    require_once "pdo.php";
    require_once "functions.php";

    //start the session
    session_start();

    /*display flash messages if things have gone right or wrong
    during the use of the application*/
    displayErrorMessage();
    displaySuccessMessage();

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Home Page</title>
        <link rel="stylesheet" href="style.css">
        <style>

        </style>
    </head>
    <body>
        <!--Navigation Bar for the web application-->
        <nav class="navbar">  
            <ul>

            <li><a href="home.php"> Home </a></li>
            <li><a href="about.php"> About Us</a></li>    
            <li><a href="student/signup.php"> Sign Up 
                And Make A Reservation </a></li>  
            <li><a href="student/login.php"> Log In </a></li>
            <li><a href="admin/login.php"> Administrator Log In</a></li>   
            <li><a href="contact.php"> Contact </a></li>  
            <li><a href="useTerms.php"> Terms of use </a></li>
            <li><a href="prices.php"> Room Prices </a></li>  
             
            </ul>  
        </nav>
        
        <!--A bit of advertising-->
        <h3>Welcome to our Student Hostel!</h3>
        <p>Experience comfortable and affordable accommodation 
        options.</p>
        <p>Enjoy delicious meals and impeccable cleaning service.</p>
        <p>Explore recreational facilities for relaxation and 
        leisure.</p>
        <p>Join our vibrant community and create unforgettable 
        memories.</p>
        <p></p>

        <!--Explanation on how to navigate the website-->
        <h3>How to navigate this application</h3>

        <p>If you want to check out the affordable prices we 
            offer, click "Room Prices".</p>
        <p>If you are a new customer, click 
            "Sign Up And Make A Reservation".</p>
        <p>If you are an existing customer, click "Log In".</p>
        <p>If you want to learn more about us, click "About".</p>
        <p>If you want to contact us, click "Contact".</p>
        <p>If you want to learn about our terms of use, click 
            "Terms of use"</p>
        <p>If you are the administrator, click "Administrator Log In"</p>
        <p>If you want to go to the home page, click "Home"</p>
        

    </body>
</html>
