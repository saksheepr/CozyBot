<?php
session_start(); 
session_unset(); 
session_destroy(); 
header("Location: Sign_Up.html"); // Redirect to login page
exit; // Stop further execution
