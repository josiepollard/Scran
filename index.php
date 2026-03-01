<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <link rel="stylesheet" type="text/css" href="styles/home.css">
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
    <h1 class="scran-hero-main-title">With SCRAN, you CAN</h1>
    <p class="scran-hero-main-subtitle">
      Make easy everyday recipes, perfect for any budget and every occasion
    </p>

   
  </div>
</section>
<!-- BANNER END -->


<!-- RANDOM RECIPES SECTION START -->
<section class="container my-5">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0">Discover Something Tasty</h2>

    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" type="button"
              data-bs-target="#recipesCarousel" data-bs-slide="prev">
        ‹
      </button>
      <button class="btn btn-outline-secondary btn-sm" type="button"
              data-bs-target="#recipesCarousel" data-bs-slide="next">
        ›
      </button>
    </div>
  </div>

  <div id="recipesCarousel" class="carousel slide"
     data-bs-ride="carousel"
     data-bs-interval="6000"
     data-bs-pause="hover">
    <div class="carousel-inner" id="recipes-carousel-inner">
      <!-- Slides injected here -->
    </div>
  </div>
</section>
<!-- RANDOM RECIPES SECTION END -->

<!-- FOOTER START -->
<?php include 'includes/footer.html'; ?>
<!-- FOOTER END -->

<script>
function truncateText(text, maxLength) {
  return text.length > maxLength ? text.slice(0, maxLength) + "..." : text;
}

function createRecipeCard(meal) {
  return `
    <div class="col">
      <div class="card h-100 shadow-sm">
        <img src="${meal.strMealThumb}" class="card-img-top recipe-card-img" alt="${meal.strMeal}">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title" title="${meal.strMeal}">
            ${truncateText(meal.strMeal, 40)}
          </h5>
          <a href="recipe.php?id=${meal.idMeal}" class="btn btn-warning mt-auto">
            View Recipe
          </a>
        </div>
      </div>
    </div>
  `;
}

// Break recipes into slides of 4
function chunkArray(arr, size) {
  const chunks = [];
  for (let i = 0; i < arr.length; i += size) {
    chunks.push(arr.slice(i, i + size));
  }
  return chunks;
}

async function getRandomRecipesCarousel() {
  const inner = document.getElementById("recipes-carousel-inner");
  inner.innerHTML = `<div class="text-center py-5">Loading recipes...</div>`;

  try {
    const count = 16;   // total recipes
    const perSlide = 4; // show 4 per slide

    const requests = Array.from({ length: count }, () =>
      fetch("https://www.themealdb.com/api/json/v1/1/random.php")
        .then(res => res.json())
    );

    const results = await Promise.all(requests);

    const meals = results
      .map(r => r?.meals?.[0] ?? null)
      .filter(Boolean);

    const slides = chunkArray(meals, perSlide);

    inner.innerHTML = slides.map((slideMeals, index) => `
      <div class="carousel-item ${index === 0 ? "active" : ""}">
        <div class="row row-cols-2 row-cols-md-2 row-cols-lg-4 g-4">
          ${slideMeals.map(createRecipeCard).join("")}
        </div>
      </div>
    `).join("");

  } catch (error) {
    console.error(error);
    inner.innerHTML = `
      <div class="text-center py-5 text-danger">
        Failed to load recipes.
      </div>
    `;
  }
}

document.addEventListener("DOMContentLoaded", getRandomRecipesCarousel);
</script>

</body>
</html>