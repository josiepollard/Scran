<?php
include "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success"=>false]);
    exit();
}

$current = $_POST["current"] ?? "";
$new = $_POST["new"] ?? "";

$stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!password_verify($current, $user["password"])) {
    echo json_encode(["success"=>false, "message"=>"Wrong current password"]);
    exit();
}

$hashed = password_hash($new, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si", $hashed, $_SESSION["user_id"]);

if($stmt->execute()){
    echo json_encode(["success"=>true, "message"=>"Password updated"]);
} else {
    echo json_encode(["success"=>false, "message"=>"Error"]);
}