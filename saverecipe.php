<?php
include "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false]);
    exit();
}

$meal_id = trim($_POST["recipe_id"] ?? "");
$user_id = $_SESSION["user_id"];

if ($meal_id === "") {
    echo json_encode(["success" => false]);
    exit();
}

$stmt = $conn->prepare("INSERT IGNORE INTO saved_recipes (user_id, meal_id) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $meal_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}

$stmt->close();