<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" href="styles/index.css">
<link rel="stylesheet" type="text/css" href="styles/allRecipes.css">

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<title>Scran</title>
<link rel="icon" type="image/x-icon" href="/images/favi.png">

</head>

<body>

<!-- NAVBAR -->
<?php include 'includes/navbar.html'; ?>


<!-- HERO -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>

  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">All Recipes</h1>
  </div>
</section>


<!-- RECIPES -->
<section class="container my-5">

<!-- SEARCH + SORT (CENTERED) -->
<div class="row mb-4 justify-content-center align-items-center g-2">

  <div class="col-auto">
    <input
      type="text"
      id="recipeSearch"
      class="form-control form-control-lg"
      placeholder="Search recipes by name..."
      style="width:350px"
    >
  </div>

  <div class="col-auto">
    <select id="sortRecipes" class="form-select form-select-lg">
      <option value="az">Sort: A → Z</option>
      <option value="za">Sort: Z → A</option>
    </select>
  </div>

</div>


<!-- RECIPES GRID -->
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4" id="recipes-container"></div>

</section>


<!-- FOOTER -->
<?php include 'includes/footer.html'; ?>


<script>

let allMeals = [];
let currentSort = "az";


function truncateText(text, maxLength) {
  return text.length > maxLength ? text.slice(0, maxLength) + "..." : text;
}


function createRecipeCard(meal) {

return `
<div class="col">
  <div class="card h-100 shadow-sm">

    <img src="${meal.strMealThumb}" 
         class="card-img-top recipe-card-img" 
         alt="${meal.strMeal}">

    <div class="card-body d-flex flex-column">

      <h5 class="card-title" title="${meal.strMeal}">
        ${truncateText(meal.strMeal,25)}
      </h5>

      <a href="recipe.php?id=${meal.idMeal}" 
         class="btn btn-warning mt-auto">
         View Recipe
      </a>

    </div>

  </div>
</div>
`;
}


function sortMeals(meals){

return [...meals].sort((a,b)=>{

if(currentSort === "az"){
return a.strMeal.localeCompare(b.strMeal);
}

else{
return b.strMeal.localeCompare(a.strMeal);
}

});

}


function renderMeals(meals){

const container = document.getElementById("recipes-container");

if(!meals.length){
container.innerHTML = `<p class="text-center">No recipes found.</p>`;
return;
}

const sortedMeals = sortMeals(meals);

container.innerHTML = sortedMeals.map(createRecipeCard).join("");

}


async function loadAllMeals(){

const container = document.getElementById("recipes-container");

container.innerHTML = `<div class="text-center py-5">Loading recipes...</div>`;

try{

const alphabet = "abcdefghijklmnopqrstuvwxyz".split("");

const requests = alphabet.map(letter =>
fetch(`https://www.themealdb.com/api/json/v1/1/search.php?f=${letter}`)
.then(res => res.json())
);

const results = await Promise.all(requests);

allMeals = results.flatMap(result => result.meals || []);

renderMeals(allMeals);

}

catch(error){

console.error(error);

container.innerHTML = `<p class="text-danger text-center">Failed to load recipes.</p>`;

}

}


function filterMeals(){

const searchValue =
document.getElementById("recipeSearch")
.value
.toLowerCase();

const filtered = allMeals.filter(meal =>
meal.strMeal.toLowerCase().includes(searchValue)
);

renderMeals(filtered);

}


document.addEventListener("DOMContentLoaded", ()=>{

loadAllMeals();

document
.getElementById("recipeSearch")
.addEventListener("input", filterMeals);

document
.getElementById("sortRecipes")
.addEventListener("change",(e)=>{

currentSort = e.target.value;

filterMeals();

});

});

</script>

</body>
</html>