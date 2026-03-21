<?php
include "config.php";
?>

<?php
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

<script id="gkt5y9">
const savedMealIds = <?php echo json_encode($savedMealIds); ?>;
</script>

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

    const isSaved = savedMealIds.includes(mealId);

    return `
      <button 
        class="btn ${isSaved ? 'btn-dark' : 'btn-warning'}"
        onclick="toggleSave('${mealId}', this)">
        ${isSaved ? 'Saved' : 'Save Recipe'}
      </button>
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

async function saveRecipe(mealId, button) {
  try {
    const response = await fetch("saveRecipe.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: "recipe_id=" + encodeURIComponent(mealId)
    });

    const data = await response.json();

    if (data.success) {
      // ✅ Change button instantly
      button.textContent = "Saved ✓";
      button.classList.remove("btn-warning");
      button.classList.add("btn-success");
      button.disabled = true;
    } else {
      alert(data.message || "Failed to save recipe");
    }

  } catch (error) {
    console.error(error);
    alert("Something went wrong");
  }
}
async function toggleSave(mealId, button) {
  try {
    const response = await fetch("toggleSave.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: "recipe_id=" + encodeURIComponent(mealId)
    });

    const data = await response.json();

    if (!data.success) {
      alert(data.message || "Error");
      return;
    }

    if (data.saved) {
      // ✅ NOW SAVED
      button.textContent = "Saved";
      button.classList.remove("btn-warning");
      button.classList.add("btn-dark");
    } else {
      // ❌ NOW REMOVED
      button.textContent = "Save Recipe";
      button.classList.remove("btn-dark");
      button.classList.add("btn-warning");
    }

  } catch (err) {
    console.error(err);
    alert("Something went wrong");
  }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>