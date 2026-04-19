<!-- 
  home page 
  * shows random recipes in a carousel
  * shows recently viewed recipes
  * shows category links
  * links to scrandom page
-->
<?php
  include "config.php";

  $savedMealIds = [];

  if (isset($_SESSION["user_id"])) {
      $stmt = $conn->prepare("SELECT meal_id FROM saved_recipes WHERE user_id = ?");
      $stmt->bind_param("i", $_SESSION["user_id"]);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) {
          $savedMealIds[] = $row["meal_id"];
      }

      $stmt->close();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>SCRAN | Home</title>
    <link rel="icon" type="image/x-icon" href="/images/favi.png">
</head>

<body class="page-home">

<!-- NAVBAR  -->
<?php include 'includes/navbar.php'; ?>


<!-- BANNER  -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>
  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">With SCRAN, you CAN</h1>
    <p class="scran-hero-main-subtitle">
      Make easy everyday recipes, perfect for any budget and every occasion
    </p>
  </div>
</section>


<!-- RECIPES CAROUSEL -->
<section class="container my-5">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0">Discover Something Tasty</h2>

    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" type="button" onclick="scrollRecipes(-1)">
        ‹
      </button>
      <button class="btn btn-outline-secondary btn-sm" type="button" onclick="scrollRecipes(1)">
        ›
      </button>
    </div>
  </div>

  <div class="position-relative">
  <div id="recipes-row" class="recipes-scroll-row">
    <!-- Cards  here -->
  </div>
</div>
</section>


<!-- SCRANDOM SECTION  -->
<section class="scrandom-section text-center py-5">
  <div class="container">
    <h2 class="mb-3">Not sure what to cook?</h2>
    <p class="mb-4">
      Let SCRAN help you pick a recipe
    </p>
    <a href="scrandom.php" class="btn btn-dark btn-lg">
      Scrandom 
    </a>
  </div>
</section>


<!-- CATEGORIES SECTION -->
<section class="container my-5">
  <h2 class="mb-4 text-center">Browse by Category</h2>
  <div class="row row-cols-2 row-cols-md-3 row-cols-lg-3 g-4">
    <!-- Breakfast -->
    <div class="col">
      <a href="breakfast.php" class="category-card">
        <div class="category-box">Breakfast</div>
      </a>
    </div>
    <!-- Desserts -->
    <div class="col">
      <a href="desserts.php" class="category-card">
        <div class="category-box">Desserts</div>
      </a>
    </div>
    <!-- Starters -->
    <div class="col">
      <a href="Starter.php" class="category-card">
        <div class="category-box">Starters</div>
      </a>
    </div>
    <!-- Side -->
    <div class="col">
      <a href="Side.php" class="category-card">
        <div class="category-box">Side Dishes</div>
      </a>
    </div>
    <!-- Vegetarian -->
    <div class="col">
      <a href="Vegetarian.php" class="category-card">
        <div class="category-box">Vegetarian</div>
      </a>
    </div>
    <!-- Vegan -->
    <div class="col">
      <a href="Vegan.php" class="category-card">
        <div class="category-box">Vegan</div>
      </a>
    </div>
  </div>
</section>


<!-- RECENTLY VIEWED -->
<section class="container my-5">
  <h2 class="mb-3">Recently Viewed</h2>
  <div id="recent-row" class="recipes-scroll-row">
    <!-- Cards here -->
  </div>
</section>


<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>



<script>
const savedMealIds = <?php echo json_encode($savedMealIds); ?>; // pass PHP array of saved meal IDs to JavaScript
const isLoggedIn = <?php echo isset($_SESSION["user_id"]) ? 'true' : 'false'; ?>; // check if user is logged in

// Shorten long recipe names
function shortenText(text, maxLength) {
  return text.length > maxLength ? text.slice(0, maxLength) + "..." : text;
}

// Create recipe card HTML
function createRecipeCard(meal) {

  const isSaved = savedMealIds.includes(meal.idMeal);// check if meal is saved by the user

  return `
    <div class="recipe-scroll-item">
      <div class="card h-100 shadow-sm">

        <img src="${meal.strMealThumb}" 
            class="card-img-top recipe-card-img" 
            alt="${meal.strMeal}">

        <div class="card-body d-flex flex-column">

          <h5 class="card-title" title="${meal.strMeal}">
            ${shortenText(meal.strMeal, 40)}
          </h5>

          <div class="mt-auto d-grid gap-2">
            <a href="recipe.php?id=${meal.idMeal}" 
              class="btn btn-warning">
              View Recipe
            </a>

            ${
              isLoggedIn
            ? `<button 
                  class="btn ${isSaved ? 'btn-dark' : 'btn-outline-secondary'}"
                  onclick="toggleSave('${meal.idMeal}', this)">
                  ${isSaved ? 'Saved' : 'Save'}
              </button>`
            : `<a href="login.php" class="btn btn-outline-secondary">
                  Log in to save
              </a>`
          }
          </div>
        </div>
      </div>
    </div>
  `;
}

// Fetch random recipes and populate carousel
async function getRandomRecipesCarousel() {
  const row = document.getElementById("recipes-row");
  row.innerHTML = `<div class="text-center py-5 w-100">Loading recipes...</div>`;

  try {
    const count = 16; // number of random recipes to fetch

    // Create an array 
    const requests = Array.from({ length: count }, () =>
      fetch("https://www.themealdb.com/api/json/v1/1/random.php") // TheMealDB API random meal
        .then(res => res.json())
    );

  const results = await Promise.all(requests); // wait for all fetches to complete
  const seen = new Set(); // to track unique meal IDs and avoid duplicates

  // Extract meals from results, filter out duplicates
  const meals = results
    .map(r => r?.meals?.[0] ?? null)
    .filter(meal => {
      if (!meal) return false;
      if (seen.has(meal.idMeal)) return false;
      seen.add(meal.idMeal);
      return true;
    });

  // Duplicate meals to create a seamless scrolling effect
  const extendedMeals = [...meals, ...meals];

  // Generate HTML for all meals and insert into the carousel row
  row.innerHTML = extendedMeals.map(createRecipeCard).join("");

    // If failure, show message
    } catch (error) {
      console.error(error);
      row.innerHTML = `
        <div class="text-center py-5 text-danger w-100">
          Failed to load recipes.
        </div>
      `;
    }
}

// carousel scroll function
function scrollRecipes(direction) {

  const row = document.getElementById("recipes-row"); // reference to the carousel row
  const firstCard = row.querySelector(".recipe-scroll-item"); // reference to the first card to calculate scroll amount

  if (!firstCard) return;

    const cardWidth = firstCard.offsetWidth; // width of one card
    const gap = 24; // gap between cards 
    const scrollAmount = cardWidth + gap; // total scroll amount to move by one card

    // Scroll the row by the calculated amount in the specified direction
    row.scrollBy({
      left: direction * scrollAmount,
      behavior: "smooth"
  });

  const maxScroll = row.scrollWidth / 2; 

  // If we've scrolled to the end of the first set of meals, jump back to the start 
  if (row.scrollLeft >= maxScroll) {
    setTimeout(() => {
      row.scrollLeft = 0;
    }, 400);
  }

  // If we're scrolling left from the start, jump to the end of the first set of meals
  if (row.scrollLeft <= 0 && direction === -1) {
    setTimeout(() => {
      row.scrollLeft = maxScroll;
    }, 400);
  }
}


// save/unsave recipe for logged in user
    async function toggleSave(mealId, button){
    
      try{ 
        button.disabled = true;// disable button to prevent multiple clicks while processing

        // Send POST request to toggleSave.php with meal ID
        const response = await fetch("toggleSave.php",{ method:"POST", headers:{ "Content-Type":"application/x-www-form-urlencoded"},body:"recipe_id=" + encodeURIComponent(mealId)});
        const data = await response.json();
        
      // If error, show message and re-enable button
      if(!data.success){
        alert(data.message || "Error");
        button.disabled = false;
        return;
      }
      
      // If saved, change button to "Saved" and add to savedMealIds array
      if(data.saved){
        button.textContent = "Saved";
        button.classList.remove("btn-outline-secondary");
        button.classList.add("btn-dark");
        savedMealIds.push(mealId);
      }

      // If unsaved, revert button and remove from savedMealIds array
      else{
        button.textContent = "Save";
        button.classList.remove("btn-dark");
        button.classList.add("btn-outline-secondary");
        const index = savedMealIds.indexOf(mealId);
        if(index > -1) savedMealIds.splice(index,1);
      }
      
      button.disabled = false; // re-enable button after processing
      }

      // If failure, show alert
      catch(err){
        console.error(err);
        alert("Something went wrong");
        button.disabled = false;
      }
    }


// scroll carousel automatically every 6 seconds
let autoScrollInterval;

function startAutoScroll(){
  autoScrollInterval = setInterval(() => {
    scrollRecipes(1);
  }, 6000); // 6 seconds
}


// load recently viewed recipes from localStorage 
async function loadRecentRecipes(){

  const row = document.getElementById("recent-row");
  let recent = JSON.parse(localStorage.getItem("recentRecipes")) || []; // get recent recipe IDs from localStorage

  // If no recent recipes, show message and return
  if(recent.length === 0){
    row.innerHTML = `<p class="text-muted">No recently viewed recipes.</p>`;
    return;
  }

  try{
    const requests = recent.map(id =>
      fetch(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${id}`) // TheMealDB API lookup by ID
        .then(res => res.json())
    );

    const results = await Promise.all(requests); // wait for all fetches to complete

    // Extract meals from results
    const meals = results
      .map(r => r.meals ? r.meals[0] : null)
      .filter(Boolean);

    row.innerHTML = meals.map(createRecipeCard).join(""); // generate HTML for meals and insert into the recent recipes row

    // If failure, show message
  } catch(err){
    console.error(err);
    row.innerHTML = `<p class="text-danger">Failed to load recent recipes.</p>`; 
  }
}

// Load recipes and start auto-scroll when page is ready
  document.addEventListener("DOMContentLoaded", () => {
    getRandomRecipesCarousel();
    loadRecentRecipes(); 
    startAutoScroll();
  });
</script>

</body>
</html>