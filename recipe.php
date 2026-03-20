

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


// =========================
// INGREDIENTS
// =========================
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


// =========================
// STEPS (NEW)
// =========================
function getSteps(instructions){

  return instructions
    .split(/\r\n|\n|\. /) // split by line or sentence
    .map(step => step.trim())
    .filter(step => step.length > 0);
}



function getYouTubeEmbed(url) {
  if (!url) return null;

  const videoId = url.split("v=")[1];
  if (!videoId) return null;

  return `https://www.youtube.com/embed/${videoId}`;
}

function getSaveButton(mealId) {

  <?php if (isset($_SESSION['user_id'])): ?>
    return `
      <form method="POST" action="saveRecipe.php">
        <input type="hidden" name="recipe_id" value="${mealId}">
        <button type="submit" class="btn btn-primary">
          Save Recipe
        </button>
      </form>
    `;
  <?php else: ?>
    return `
      <a href="login.php" class="btn btn-outline-secondary">
        Log in to save
      </a>
    `;
  <?php endif; ?>

}

// =========================
// LOAD RECIPE
// =========================
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
    const videoUrl = getYouTubeEmbed(meal.strYoutube);

    // Ingredients
    const ingredientsList = getIngredients(meal)
      .map(item => `<li>${item}</li>`)
      .join("");

    // Steps
    const stepsList = getSteps(meal.strInstructions)
      .map(step => `<li class="mb-2">${step}</li>`)
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

          <div class="mt-3">
  ${getSaveButton(meal.idMeal)}
</div>

          <h4 class="mt-4">Ingredients</h4>
          <ul>${ingredientsList}</ul>

        </div>

      </div>

      <!-- INSTRUCTIONS -->
<div class="mt-5">
  <h3>Instructions</h3>
  <ol class="mt-3">
    ${stepsList}
  </ol>
</div>

<!-- VIDEO -->
${videoUrl ? `
<div class="mt-5">
  <h3>Video Tutorial</h3>

  <div class="ratio ratio-16x9 mt-3">
    <iframe src="${videoUrl}" 
            title="YouTube video"
            allowfullscreen>
    </iframe>
  </div>
</div>
` : ""}
    `;

  } catch(error){

    console.error(error);
    container.innerHTML = "<p class='text-danger text-center'>Failed to load recipe.</p>";

  }

}

document.addEventListener("DOMContentLoaded", loadRecipe);

</script>

</body>
</html>