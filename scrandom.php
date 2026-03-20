<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Scran</title>
    <link rel="icon" type="image/x-icon" href="/images/favi.png">

</head>

<body class="page-scrandom">

<!-- NAVBAR -->
<!-- This loads the navigation bar from another file so it can be reused across pages -->
<?php include 'includes/navbar.php'; ?>


<!-- HERO BANNER -->
<!-- This is the top banner section of the page -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">

  <!-- Overlay used for styling over the banner image -->
  <div class="scran-hero-main-overlay"></div>

  <!-- Main banner content -->
  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">Scrandom!</h1>
    <p>Can't decide what to eat? Pick your favourite until one scran remains.</p>
  </div>

</section>



<!-- SCRANDOM GAME SECTION -->

<section class="scrandom-game container text-center py-5">
<h2>Choose Your Scran</h2>
<div class="recipe-container d-flex justify-content-center align-items-center gap-4 flex-wrap mt-4" id="gameArea">

<!-- Recipe option A will appear here -->
<div class="recipe-card" id="recipeA"></div>

<!-- VS text between the recipes -->
<div class="vs fs-2 fw-bold">VS</div>

<!-- Recipe option B will appear here -->
<div class="recipe-card" id="recipeB"></div>

</div>

</section>



<!-- FOOTER -->
<!-- Loads footer from reusable file -->
<?php include 'includes/footer.html'; ?>



<script>
/*  Variables for the game */

// This will store recipes from the API
let recipes = [];

// This is the list used during the game
let pool = [];

// The two recipes being shown
let optionA;
let optionB;



/* loads food from the API */

async function loadRecipes(){

// Loop 10 times to get 10 random meals
for(let i = 0; i < 10; i++){

// Gets the data from TheMealDB API
const response = await fetch(
"https://www.themealdb.com/api/json/v1/1/random.php"
);

// Convert the response into JSON
const data = await response.json();

// Add the recipe to our recipes array
recipes.push(data.meals[0]);

}

// Once recipes are loaded, start the game
startGame();

}



/* START GAME */

function startGame(){

// Copy the recipes into the pool array
pool = [...recipes];

// Start the first round
nextRound();

}



/* Next round */

function nextRound(){

// If only one recipe remains, the game is over.
if(pool.length === 1){

showWinner(pool[0]);
return;

}

// Remove the first two recipes from the pool
optionA = pool.shift();
optionB = pool.shift();

// Display those recipes on screen
displayOptions(optionA, optionB);

}



/* Display both options */

function displayOptions(a,b){

// Fill the first recipe card
document.getElementById("recipeA").innerHTML = `

<div class="card shadow" style="width:280px;">
<img src="${a.strMealThumb}" class="card-img-top">
<div class="card-body">
<h5 class="card-title">${a.strMeal}</h5>

<!-- Button that selects recipe A -->
<button class="btn btn-warning" onclick="choose('A')">
Choose
</button>

</div>
</div>

`;


// Fill the second recipe card
document.getElementById("recipeB").innerHTML = `

<div class="card shadow" style="width:280px;">
<img src="${b.strMealThumb}" class="card-img-top">
<div class="card-body">
<h5 class="card-title">${b.strMeal}</h5>

<!-- Button that selects recipe B -->
<button class="btn btn-warning" onclick="choose('B')">
Choose
</button>

</div>
</div>

`;

}



/* Choice made by User */

function choose(choice){

// If user picked recipe A
if(choice === "A"){

// Put recipe A back into the pool
pool.push(optionA);

}else{

// Otherwise recipe B goes back in
pool.push(optionB);

}

// Start the next round
nextRound();

}



/* Show ultimate choice */

function showWinner(recipe){

// Replace the game area with the final result
document.getElementById("gameArea").innerHTML = `

<div class="text-center">

<h2>Your Scran Is...</h2>

<img src="${recipe.strMealThumb}" style="width:300px;border-radius:12px">

<h1 class="mt-3">${recipe.strMeal}</h1>

<br>

<!-- Button reloads the page to start again -->
<button class="btn btn-warning" onclick="location.reload()">
Play Again
</button>

</div>

`;

}



/* Start after page loads */

// When the page loads, start loading recipes
window.onload = loadRecipes;

</script>

</body>
</html>