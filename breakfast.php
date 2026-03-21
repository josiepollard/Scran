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

<body class="page-breakfast">

<!-- NAVBAR -->
<?php include 'includes/navbar.php'; ?>


<!-- HERO -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>

  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">Breakfast</h1>
  </div>
</section>


<!-- BREAKFAST RECIPES -->
<section class="container my-5">

<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4" id="breakfast-container">
</div>

</section>


<!-- FOOTER -->
<?php include 'includes/footer.html'; ?>

<script>
const savedMealIds = <?php echo json_encode($savedMealIds); ?>;
const isLoggedIn = <?php echo isset($_SESSION["user_id"]) ? 'true' : 'false'; ?>;
</script>

<script>

function truncateText(text, maxLength){
  return text.length > maxLength ? text.slice(0,maxLength) + "..." : text;
}


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
        ${truncateText(meal.strMeal,25)}
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


async function loadBreakfastRecipes(){

const container = document.getElementById("breakfast-container");

container.innerHTML = `<div class="text-center py-5">Loading breakfast recipes...</div>`;

try{

const response = await fetch("https://www.themealdb.com/api/json/v1/1/filter.php?c=Breakfast");

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

</script>

</body>
</html>