<?php
include "config.php";
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success"=>false, "message"=>"Login required"]);
    exit();
}

$user_id = $_SESSION["user_id"];
$meal_id = $_POST["meal_id"] ?? "";
$comment = trim($_POST["comment"] ?? "");

// validate
if(strlen($comment) < 3){
    echo json_encode(["success"=>false, "message"=>"Review too short"]);
    exit();
}

// check existing
$check = $conn->prepare("SELECT id FROM reviews WHERE user_id=? AND meal_id=?");
$check->bind_param("is", $user_id, $meal_id);
$check->execute();

if($check->get_result()->num_rows > 0){
    echo json_encode(["success"=>false, "message"=>"You already reviewed this recipe"]);
    exit();
}

// insert
$stmt = $conn->prepare("INSERT INTO reviews (user_id, meal_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $meal_id, $comment);

echo json_encode(["success"=>$stmt->execute()]);