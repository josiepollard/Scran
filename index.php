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
  <h2 class="text-center mb-4">Discover Something Tasty</h2>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4" id="random-recipes">
    <!-- Recipes will load here -->
  </div>
</section>
<!-- RANDOM RECIPES SECTION END -->

<!-- FOOTER START -->
<?php include 'includes/footer.html'; ?>
<!-- FOOTER END -->

<script>
async function getRandomRecipes() {
    const container = document.getElementById("random-recipes");
    container.innerHTML = "";

    try {
        const requests = [];

        // Get 12
        for (let i = 0; i < 12; i++) {
            requests.push(
                fetch("https://www.themealdb.com/api/json/v1/1/random.php")
                .then(res => res.json())
            );
        }

        // Wait for all 12 to finish
        const results = await Promise.all(requests);

        results.forEach(result => {
            const meal = result.meals[0];
           

        function truncateText(text, maxLength) {
          return text.length > maxLength
            ? text.slice(0, maxLength) + "..."
            : text;
        }

        const recipeCard = `
          <div class="col">
            <div class="card h-100 shadow-sm">
              <img src="${meal.strMealThumb}" class="card-img-top" alt="${meal.strMeal}">
              <div class="card-body d-flex flex-column">
                  <h5 class="card-title" title="${meal.strMeal}">
                      ${truncateText(meal.strMeal, 25)}
                  </h5>
                  <a href="recipe.php?id=${meal.idMeal}" class="btn btn-warning mt-auto">
                      View Recipe
                  </a>
            </div>
            </div>
          </div>
        `;

            container.innerHTML += recipeCard;
        });

    } catch (error) {
        container.innerHTML = "<p class='text-center'>Failed to load recipes. Please try again.</p>";
        console.error(error);
    }
}
// Load recipes when page loads
document.addEventListener("DOMContentLoaded", getRandomRecipes);
</script>


</body>
</html>