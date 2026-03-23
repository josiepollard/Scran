<?php
include "config.php";

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success"=>false]);
    exit();
}

// Delete user account from database
$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION["user_id"]);

// Execute delete and return JSON response
if($stmt->execute()){
    session_destroy();
    echo json_encode(["success"=>true]);
} else {
    echo json_encode(["success"=>false]);
}