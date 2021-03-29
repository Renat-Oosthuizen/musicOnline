<?php
    // Start the session.
    session_start();

    //Clear all session variables.
    session_unset();

    //Send user to the login page.
    header("Location: index.html");

?>