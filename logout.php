<?php
include "config.php"; // database connection

// Clear all session variables
$_SESSION = []; 
session_unset();
session_destroy();

// Redirect to homepage after logout
header("Location: index.php");
exit();