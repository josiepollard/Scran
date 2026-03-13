<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <link rel="stylesheet" type="text/css" href="styles/desserts.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Scran</title>
    <link rel="icon" type="image/x-icon" href="/images/favi.png">

</head>

<body>

<!-- NAVBAR START -->
<!-- uses includes/navbar.php which we will reuse throughout the website-->
<?php include 'includes/navbar.html'; ?>
<!-- NAVBAR END -->


<!-- BANNER START -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>

  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">Desserts</h1>
  </div>
</section>
<!-- BANNER END -->

<!-- BREAKFAST RECIPES -->
<section class="container my-5">

<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4" id="breakfast-container">
</div>

</section>


<!-- FOOTER -->
<?php include 'includes/footer.html'; ?>


<script>

function truncateText(text, maxLength){
  return text.length > maxLength ? text.slice(0,maxLength) + "..." : text;
}


function createRecipeCard(meal){

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


async function loadBreakfastRecipes(){

const container = document.getElementById("breakfast-container");

container.innerHTML = `<div class="text-center py-5">Loading breakfast recipes...</div>`;

try{

const response = await fetch("https://www.themealdb.com/api/json/v1/1/filter.php?c=Dessert");

const data = await response.json();

const meals = data.meals || [];

container.innerHTML = meals.map(createRecipeCard).join("");

}

catch(error){

console.error(error);

container.innerHTML = `<p class="text-danger text-center">Failed to load recipes.</p>`;

}

}


document.addEventListener("DOMContentLoaded", loadBreakfastRecipes);

</script>


</body>
</html>