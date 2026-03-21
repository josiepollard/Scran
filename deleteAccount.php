<?php
include "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success"=>false]);
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION["user_id"]);

if($stmt->execute()){
    session_destroy();
    echo json_encode(["success"=>true]);
} else {
    echo json_encode(["success"=>false]);
}