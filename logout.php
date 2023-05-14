<?php
    //start the session
    session_start();
    //destroy the session values
    session_destroy();
    //redirect to the home page
    header("Location: home.php");
    return;
?>