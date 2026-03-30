<?php
include "config.php";
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success"=>false]);
    exit();
}

$user_id = $_SESSION["user_id"];
$meal_id = $_POST["meal_id"];

$stmt = $conn->prepare("DELETE FROM reviews WHERE user_id=? AND meal_id=?");
$stmt->bind_param("is", $user_id, $meal_id);

echo json_encode(["success"=>$stmt->execute()]);