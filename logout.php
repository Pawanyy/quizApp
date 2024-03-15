<?php
include_once 'bootstrap.php';

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the login page or any other desired page after logout
reloadPage('login.php')
?>