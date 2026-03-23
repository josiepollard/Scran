<?php
include "config.php";

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success"=>false]);
    exit();
}

$name = trim($_POST["name"] ?? ""); // Get new name from POST data and trim whitespace

$stmt = $conn->prepare("UPDATE users SET name=? WHERE id=?"); // Prepare SQL statement to update user's name
$stmt->bind_param("si", $name, $_SESSION["user_id"]);

if($stmt->execute()){
    $_SESSION["user_name"] = $name;
    echo json_encode(["success"=>true]);
} else {
    echo json_encode(["success"=>false]);
}