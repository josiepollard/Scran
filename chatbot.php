<?php
header("Content-Type: application/json");

// Get messages
$requestData = json_decode(file_get_contents("php://input"), true);
$messages = $requestData["messages"] ?? [];

$lastMessage = "";
if (!empty($messages)) {
  $lastMessage = end($messages)["content"];
}

// Fetch recipe data
$mealData = "";

$searchUrl = "https://www.themealdb.com/api/json/v1/1/search.php?s=" . urlencode($lastMessage);
$response = @file_get_contents($searchUrl);

if ($response !== false) {
  $mealResponse = json_decode($response, true);

  if (!empty($mealResponse["meals"])) {
    $meal = $mealResponse["meals"][0];

    $mealData = "Recipe found:\n";
    $mealData .= "Name: " . $meal["strMeal"] . "\n";
    $mealData .= "Category: " . $meal["strCategory"] . "\n";
    $mealData .= "Instructions: " . substr($meal["strInstructions"], 0, 300);
  }
}

// ADD API KEY HERE
$apiKey = ""; // removed for security


// Prepare request
$postData = [
  "model" => "gpt-4.1-mini",
  "messages" => array_merge(
    [
      [
        "role" => "system",
        "content" => "You are SCRAN AI, a cooking assistant.

Only answer cooking-related questions.

If a user asks for a recipe, prioritise using the MealDB data.
If none exists, give general advice.

Continue conversations naturally.

Use this recipe data if relevant:\n" . $mealData
      ]
    ],
    $messages
  )
];

// cURL request
$ch = curl_init("https://api.openai.com/v1/chat/completions");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $apiKey",
  "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$response = curl_exec($ch);

// ❗ Debug if request fails
if ($response === false) {
  echo json_encode(["reply" => "API request failed"]);
  exit;
}

curl_close($ch);

$result = json_decode($response, true);

// ❗ Debug if API error
if (isset($result["error"])) {
  echo json_encode(["reply" => "OpenAI error: " . $result["error"]["message"]]);
  exit;
}

$reply = $result["choices"][0]["message"]["content"] ?? "Sorry, no response.";

echo json_encode(["reply" => $reply]);