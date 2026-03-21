<?php
include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$savedMealIds = [];

$stmt = $conn->prepare("SELECT meal_id FROM saved_recipes WHERE user_id = ORDER BY created_at DESC");
$stmt = $conn->prepare("SELECT meal_id FROM saved_recipes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $savedMealIds[] = $row["meal_id"];
}

$stmt->close();
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
  <title>Saved Recipes - SCRAN</title>
  <link rel="icon" type="image/x-icon" href="/images/favi.png">
</head>
<body class="page-saved">

<?php include 'includes/navbar.php'; ?>

<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>
  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">Saved Recipes</h1>
  </div>
</section>

<section class="container my-5">
  <div id="saved-recipes-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <div class="text-center py-5">Loading saved recipes...</div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
const savedMealIds = <?php echo json_encode($savedMealIds); ?>;

function createSavedRecipeCard(meal) {
  return `
    <div class="col">
      <div class="card h-100 shadow-sm">
        <img src="${meal.strMealThumb}" class="card-img-top recipe-card-img" alt="${meal.strMeal}">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">${meal.strMeal}</h5>
          <p class="text-muted">${meal.strCategory || ""} ${meal.strArea ? "| " + meal.strArea : ""}</p>

          <div class="mt-auto d-grid gap-2">
            <a href="recipe.php?id=${meal.idMeal}" class="btn btn-warning">View Recipe</a>

            <form method="POST" action="removeSavedRecipe.php">
              <input type="hidden" name="recipe_id" value="${meal.idMeal}">
              <button type="submit" class="btn btn-outline-danger w-100">Remove</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  `;
}

async function loadSavedRecipes() {
  const container = document.getElementById("saved-recipes-container");

  if (!savedMealIds.length) {
    container.innerHTML = `<p class="text-center w-100">You have no saved recipes yet.</p>`;
    return;
  }

  try {
    const requests = savedMealIds.map(id =>
      fetch(`https://www.themealdb.com/api/json/v1/1/lookup.php?i=${id}`)
        .then(res => res.json())
    );

    const results = await Promise.all(requests);

    const meals = results
      .map(result => result.meals ? result.meals[0] : null)
      .filter(Boolean);

    if (!meals.length) {
      container.innerHTML = `<p class="text-center w-100">No saved recipes found.</p>`;
      return;
    }

    container.innerHTML = meals.map(createSavedRecipeCard).join("");
  } catch (error) {
    console.error(error);
    container.innerHTML = `<p class="text-danger text-center w-100">Failed to load saved recipes.</p>`;
  }
}

document.addEventListener("DOMContentLoaded", loadSavedRecipes);
</script>
</body>
</html>