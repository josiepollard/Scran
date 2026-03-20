<?php
include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: recipes.php");
    exit();
}

$meal_id = trim($_POST["recipe_id"] ?? "");
$user_id = $_SESSION["user_id"];

if ($meal_id === "") {
    header("Location: recipes.php");
    exit();
}

$stmt = $conn->prepare("INSERT IGNORE INTO saved_recipes (user_id, meal_id) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $meal_id);
$stmt->execute();
$stmt->close();

header("Location: savedRecipes.php");
exit();