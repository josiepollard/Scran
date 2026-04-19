<?php
include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: savedRecipes.php");
    exit();
}

$meal_id = trim($_POST["recipe_id"] ?? ""); 
$user_id = $_SESSION["user_id"];

// Only if meal_id is provided
if ($meal_id !== "") {
    $stmt = $conn->prepare("DELETE FROM saved_recipes WHERE user_id = ? AND meal_id = ?");
    $stmt->bind_param("is", $user_id, $meal_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: savedRecipes.php"); 
exit();