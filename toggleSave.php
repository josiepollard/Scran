<?php
include "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

$meal_id = $_POST["recipe_id"] ?? "";
$user_id = $_SESSION["user_id"];

if (!$meal_id) {
    echo json_encode(["success" => false]);
    exit();
}

// Check if already saved
$stmt = $conn->prepare("SELECT id FROM saved_recipes WHERE user_id = ? AND meal_id = ?");
$stmt->bind_param("is", $user_id, $meal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
   
    $stmt = $conn->prepare("DELETE FROM saved_recipes WHERE user_id = ? AND meal_id = ?");
    $stmt->bind_param("is", $user_id, $meal_id);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "saved" => false
    ]);

} else {
    
    $stmt = $conn->prepare("INSERT INTO saved_recipes (user_id, meal_id) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $meal_id);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "saved" => true
    ]);
}