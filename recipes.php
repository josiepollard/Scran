<!-- 
  All Recipes page 
  * shows all recipes from TheMealDB API 
  * includes search and sort functionality
-->

<?php
  include "config.php"; // db connection
  $savedMealIds = [];// array to hold saved recipe ids

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
  <title>SCRAN | All Recipes</title>
  <link rel="icon" type="image/x-icon" href="/images/favi.png">
</head>

<body class="page-recipes">

<!-- navbar -->
<?php include 'includes/navbar.php'; ?>

<!-- Banner -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>

  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">All Recipes</h1>
  </div>
</section>

<!-- recipes container -->
<section class="container my-5">

  <!-- search and sort -->
  <div class="d-flex justify-content-center align-items-center gap-2 mb-4 flex-wrap">
    <input
      type="text"
      id="recipeSearch"
      class="form-control form-control-lg"
      placeholder="Search recipes by name..."
      style="width:350px"
    >
  
    <select id="sortRecipes" class="form-select form-select-lg" style="width:220px">
      <option value="az">Sort: A → Z</option>
      <option value="za">Sort: Z → A</option>
      <option value="newest">Sort: Newest</option>
      <option value="oldest">Sort: Oldest</option>
    </select>
  </div>

  <!-- recipes grid -->
  <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4" id="recipes-container"></div>
</section>

<!-- footer -->
<?php include 'includes/footer.php'; ?>



<script>
  const savedMealIds = <?php echo json_encode($savedMealIds); ?>; // pass PHP array to JS
  const isLoggedIn = <?php echo isset($_SESSION["user_id"]) ? 'true' : 'false'; ?>; // check if user is logged in

let allMeals = []; // will hold fetched meals
let currentSort = "az"; // default sort


function shortenText(text, maxLength) {
  return text.length > maxLength ? text.slice(0, maxLength) + "..." : text;
}

// Create recipe card HTML
function createRecipeCard(meal){

  const isSaved = savedMealIds.includes(meal.idMeal);

  return `
  <div class="col">
    <div class="card h-100 shadow-sm">

      <img src="${meal.strMealThumb}" 
          class="card-img-top recipe-card-img" 
          alt="${meal.strMeal}">

      <div class="card-body d-flex flex-column">

        <h5 class="card-title" title="${meal.strMeal}">
          ${shortenText(meal.strMeal,25)}
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


// sort recipes 
function sortMeals(meals){

  // Sort by newest or oldest based on meal ID (assuming higher ID is newer)
  if(currentSort === "newest"){
    return [...meals].sort((a,b)=> Number(b.idMeal) - Number(a.idMeal));
  }

  if(currentSort === "oldest"){
    return [...meals].sort((a,b)=> Number(a.idMeal) - Number(b.idMeal));
  }

  return [...meals].sort((a,b)=>{

  // Sort alphabetically by meal name
    if(currentSort === "az"){
    return a.strMeal.localeCompare(b.strMeal);
    }
    else{
    return b.strMeal.localeCompare(a.strMeal);
    }
  });
}


// render meals to page
function renderMeals(meals){

  const container = document.getElementById("recipes-container"); // reference to HTML container for recipe cards

  // If no meals to show, display message
  if(!meals.length){
  container.innerHTML = `<p class="text-center">No recipes found.</p>`;

  return;
  }

  const sortedMeals = sortMeals(meals); // sort meals based on current sort option
  container.innerHTML = sortedMeals.map(createRecipeCard).join(""); // create HTML for each meal and add to container

}


// load all meals from API 
async function loadAllMeals(){

  const container = document.getElementById("recipes-container"); // reference to HTML container for recipe cards
  container.innerHTML = `<div class="text-center py-5">Loading recipes...</div>`;

  try{
    const alphabet = "abcdefghijklmnopqrstuvwxyz".split(""); // array of letters to fetch meals starting with each letter
    const requests = alphabet.map(letter =>
    fetch(`https://www.themealdb.com/api/json/v1/1/search.php?f=${letter}`) // fetch meals starting with current letter
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


// search filter meals by name
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