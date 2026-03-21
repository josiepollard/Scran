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
    <title>Scran</title>
    <link rel="icon" type="image/x-icon" href="/images/favi.png">

</head>

<body class="page-home">

<!-- NAVBAR START -->
<!-- uses includes/navbar.php which we will reuse throughout the website-->
<?php include 'includes/navbar.php'; ?>
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
    <!-- Cards injected here -->
  </div>
</div>

</section>
<!-- RANDOM RECIPES SECTION END -->



<!-- CATEGORIES SECTION START -->
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
<!-- CATEGORIES SECTION END -->






<!-- FOOTER START -->
<?php include 'includes/footer.html'; ?>
<!-- FOOTER END -->


<script>
const savedMealIds = <?php echo json_encode($savedMealIds); ?>;
const isLoggedIn = <?php echo isset($_SESSION["user_id"]) ? 'true' : 'false'; ?>;
</script>

<script>
function truncateText(text, maxLength) {
  return text.length > maxLength ? text.slice(0, maxLength) + "..." : text;
}

function createRecipeCard(meal) {

const isSaved = savedMealIds.includes(meal.idMeal);

return `
  <div class="recipe-scroll-item">
    <div class="card h-100 shadow-sm">

      <img src="${meal.strMealThumb}" 
           class="card-img-top recipe-card-img" 
           alt="${meal.strMeal}">

      <div class="card-body d-flex flex-column">

        <h5 class="card-title" title="${meal.strMeal}">
          ${truncateText(meal.strMeal, 40)}
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


async function getRandomRecipesCarousel() {
  const row = document.getElementById("recipes-row");
  row.innerHTML = `<div class="text-center py-5 w-100">Loading recipes...</div>`;

  try {
    const count = 16;

    const requests = Array.from({ length: count }, () =>
      fetch("https://www.themealdb.com/api/json/v1/1/random.php")
        .then(res => res.json())
    );

    const results = await Promise.all(requests);

const seen = new Set();

const meals = results
  .map(r => r?.meals?.[0] ?? null)
  .filter(meal => {
    if (!meal) return false;
    if (seen.has(meal.idMeal)) return false;
    seen.add(meal.idMeal);
    return true;
  });





    // duplicate list for looping
const extendedMeals = [...meals, ...meals];

row.innerHTML = extendedMeals.map(createRecipeCard).join("");

  } catch (error) {
    console.error(error);
    row.innerHTML = `
      <div class="text-center py-5 text-danger w-100">
        Failed to load recipes.
      </div>
    `;
  }
}

function scrollRecipes(direction) {
  const row = document.getElementById("recipes-row");
  const firstCard = row.querySelector(".recipe-scroll-item");

  if (!firstCard) return;

  const cardWidth = firstCard.offsetWidth;
  const gap = 24;
  const scrollAmount = cardWidth + gap;

  row.scrollBy({
    left: direction * scrollAmount,
    behavior: "smooth"
  });

  // 🔁 LOOP RESET
  const maxScroll = row.scrollWidth / 2;

  if (row.scrollLeft >= maxScroll) {
    setTimeout(() => {
      row.scrollLeft = 0;
    }, 400);
  }

  if (row.scrollLeft <= 0 && direction === -1) {
    setTimeout(() => {
      row.scrollLeft = maxScroll;
    }, 400);
  }
}



async function toggleSave(mealId, button){

try{

button.disabled = true;

const response = await fetch("toggleSave.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"recipe_id=" + encodeURIComponent(mealId)
});

const data = await response.json();

if(!data.success){
alert(data.message || "Error");
button.disabled = false;
return;
}

if(data.saved){

button.textContent = "Saved";
button.classList.remove("btn-outline-secondary");
button.classList.add("btn-dark");

savedMealIds.push(mealId);

}
else{

button.textContent = "Save";
button.classList.remove("btn-dark");
button.classList.add("btn-outline-secondary");

const index = savedMealIds.indexOf(mealId);
if(index > -1) savedMealIds.splice(index,1);

}

button.disabled = false;

}
catch(err){

console.error(err);
alert("Something went wrong");
button.disabled = false;

}
}

let autoScrollInterval;

function startAutoScroll(){
  autoScrollInterval = setInterval(() => {
    scrollRecipes(1);
  }, 6000); // 6 seconds
}


document.addEventListener("DOMContentLoaded", () => {
  getRandomRecipesCarousel();
  startAutoScroll();
});
</script>

</body>
</html>