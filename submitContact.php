<?php
include "config.php"; //databse connection

header('Content-Type: application/json');

// Get and sanitize input
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$message = trim($_POST["message"] ?? "");


// validation to check if all fields are filled 
if (!$name || !$email || !$message) {
    echo json_encode(["success"=>false]);
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success"=>false]);
    exit();
}

// insert message into database using prepared statement to prevent SQL injection
$stmt = $conn->prepare("
  INSERT INTO contact_messages (name, email, message)
  VALUES (?, ?, ?)
");

// bind parameters and execute statement
$stmt->bind_param("sss", $name, $email, $message); // "sss" means all three parameters are strings

// return success or failure response
if($stmt->execute()){
    echo json_encode(["success"=>true]);
} else {
    echo json_encode(["success"=>false]);
}