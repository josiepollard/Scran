<?php
include "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success"=>false, "message"=>"You must be logged in"]);
    exit();
}

$user_id = $_SESSION["user_id"];
$meal_id = $_POST["meal_id"] ?? "";
$rating = intval($_POST["rating"] ?? 0);
$comment = trim($_POST["comment"] ?? "");

// 🔴 Check if user already reviewed
$check = $conn->prepare("SELECT id FROM reviews WHERE user_id=? AND meal_id=?");
$check->bind_param("is", $user_id, $meal_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "success"=>false,
        "message"=>"You have already reviewed this recipe"
    ]);
    exit();
}

// ✅ Insert review
$stmt = $conn->prepare(
  "INSERT INTO reviews (user_id, meal_id, rating, comment) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("isis", $user_id, $meal_id, $rating, $comment);

if($stmt->execute()){
    echo json_encode(["success"=>true]);
} else {
    echo json_encode(["success"=>false, "message"=>"Failed to save review"]);
}