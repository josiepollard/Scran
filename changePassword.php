<?php
include "config.php";

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success"=>false]);
    exit();
}

// Get current and new password from POST data
$current = $_POST["current"] ?? ""; 
$new = $_POST["new"] ?? "";

// fetch current hashed password for logged in user
$stmt = $conn->prepare("SELECT password FROM users WHERE id=?"); 
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Verify current password
if (!password_verify($current, $user["password"])) {
    echo json_encode(["success"=>false, "message"=>"Wrong current password"]);
    exit();
}

// Hash new password
$hashed = password_hash($new, PASSWORD_DEFAULT); 

// Update password in database
$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si", $hashed, $_SESSION["user_id"]);

// Execute update and return JSON response
if($stmt->execute()){
    echo json_encode(["success"=>true, "message"=>"Password updated"]);
} else {
    echo json_encode(["success"=>false, "message"=>"Error"]);
}