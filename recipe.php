<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styles/index.css">
<title>Recipe</title>
</head>

<body>

<!-- NAVBAR -->
<?php include 'includes/navbar.php'; ?>


<!-- RECIPE SECTION -->
<section class="container my-5" id="recipe-container">

  <div class="text-center py-5">
    Loading recipe...
  </div>

</section>


<!-- FOOTER -->
<?php include 'includes/footer.html'; ?>


<script>

// Get ID from URL
const params = new URLSearchParams(window.location.search);
const mealId = params.get("id");


function getIngredients(meal){

let ingredients = [];

for(let i = 1; i <= 20; i++){
  const ingredient = meal[`strIngredient${i}`];
  const measure = meal[`strMeasure${i}`];

  if(ingredient && ingredient.trim() !== ""){
    ingredients.push(`${measure} ${ingredient}`);
  }
}

return ingredients;
}


async function loadRecipe(){

const container = document.getElementById("recipe-container");

if(!mealId){
  container.innerHTML = "<p class='text-center'>No recipe found.</p>";
  return;
}

try{

const res = await fetch(
  `https://www.themealdb.com/api/json/v1/1/lookup.php?i=${mealId}`
);

const data = await res.json();
const meal = data.meals[0];

const ingredientsList = getIngredients(meal)
  .map(item => `<li>${item}</li>`)
  .join("");

container.innerHTML = `
  <div class="row">

    <!-- IMAGE -->
    <div class="col-md-6">
      <img src="${meal.strMealThumb}" class="img-fluid rounded mb-4">
    </div>

    <!-- DETAILS -->
    <div class="col-md-6">

      <h1>${meal.strMeal}</h1>

      <p class="text-muted">
        ${meal.strCategory} | ${meal.strArea}
      </p>

      <h4 class="mt-4">Ingredients</h4>
      <ul>${ingredientsList}</ul>

    </div>

  </div>

  <!-- INSTRUCTIONS -->
  <div class="mt-5">
    <h3>Instructions</h3>
    <p>${meal.strInstructions}</p>
  </div>
`;

}catch(error){

console.error(error);
container.innerHTML = "<p class='text-danger text-center'>Failed to load recipe.</p>";

}

}


document.addEventListener("DOMContentLoaded", loadRecipe);

</script>

</body>
</html>