<!-- 
individual recipe page. 
* loads recipe details from TheMealDB API based on ID 
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

 $reviews = [];

$user_id = $_SESSION["user_id"] ?? 0;

$stmt = $conn->prepare("
  SELECT reviews.*, users.name 
  FROM reviews 
  JOIN users ON users.id = reviews.user_id
  WHERE meal_id = ?
  ORDER BY (reviews.user_id = ?) DESC, created_at DESC
");

$stmt->bind_param("si", $_GET["id"], $user_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
  $reviews[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="styles/index.css">
  <title>SCRAN</title>
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
<?php include 'includes/footer.php'; ?>



<script>
const savedMealIds = <?php echo json_encode($savedMealIds); ?>; 
const params = new URLSearchParams(window.location.search); 
const mealId = params.get("id"); 


//=================================
// ingredients 
//=================================
function getIngredients(meal){

  let ingredients = []; // array to hold ingredient strings

  // loop through possible ingredient slots 
  // mealdb API has up to 20 ingredients
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
// steps
// =========================
function getSteps(instructions){

  const text = instructions.replace(/\r/g, ""); // remove carriage returns

  // check for common step delimiters and split accordingly
  if(/step\s*\d+/i.test(text)){
    return text
      .split(/step\s*\d+/i)
      .map(step => step.replace(/step\s*\d+/i, "").trim())
      .filter(step => step.length > 0);
  }

  if(/\n?\d+[\.\)]\s/.test(text)){
    return text
      .split(/\n?\d+[\.\)]\s/)
      .map(step => step.trim())
      .filter(step => step.length > 0);
  }

  return text
    .split(/\r\n|\n|\. /)
    .map(step => step.trim())
    .filter(step => step.length > 0);
}


//=================================
// youtube embed
//=================================
function getYouTubeEmbed(url) {
  if (!url) return null; 

  const videoId = url.split("v=")[1]; // extract video ID from URL
  if (!videoId) return null; 

  return `https://www.youtube.com/embed/${videoId}`; // return embed URL with ID appended
}



//=================================
// youtube embed
//=================================
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

  // if no meal ID provided, show error message
  if(!mealId){
    container.innerHTML = "<p class='text-center'>No recipe found.</p>";
    return;
  }

  try{
    const res = await fetch(
      `https://www.themealdb.com/api/json/v1/1/lookup.php?i=${mealId}` // fetch recipe details from API using meal ID
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

      <div class="mt-5">
        <h3>Instructions</h3>
        <ol class="mt-3">
          ${stepsList}
        </ol>
      </div>

      

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

<div class="mt-5">

  <h3>Reviews</h3>

  <?php if(isset($_SESSION["user_id"])): ?>
    
    <div class="card p-3 mb-3">
      <textarea id="reviewText" class="form-control mb-2" placeholder="Write your review..."></textarea>
      <button onclick="submitReview()" class="btn btn-dark">Post Review</button>
      <div id="reviewFeedback"></div>
    </div>

  <?php else: ?>

    <p><a href="login.php">Log in</a> to leave a review</p>

  <?php endif; ?>

  <div id="reviewsList">

 <?php foreach($reviews as $r): ?>

  <div class="card p-3 mb-2 <?= (isset($_SESSION["user_id"]) && $r["user_id"] == $_SESSION["user_id"]) ? 'border-dark' : '' ?>">

    <!-- HEADER: NAME (left) + DATE + TRASH (right stacked) -->
    <div class="d-flex justify-content-between align-items-start">

      <!-- LEFT: NAME -->
      <strong>
        <?= htmlspecialchars($r["user_id"] == ($_SESSION["user_id"] ?? 0) ? "You" : $r["name"]) ?>
      </strong>

      <!-- RIGHT: DATE + TRASH ICON STACKED -->
      <div class="text-end">

        <small class="text-muted d-block">
          <?= date("d M Y", strtotime($r["created_at"])) ?>
        </small>

        <?php if(isset($_SESSION["user_id"]) && $r["user_id"] == $_SESSION["user_id"]): ?>
          <button onclick="deleteReview()" 
                  class="btn btn-sm btn-link text-danger p-0 mt-1"
                  title="Delete review">
            <i class="bi bi-trash"></i>
          </button>
        <?php endif; ?>

      </div>

    </div>

    <!-- COMMENT -->
    <p class="mt-2 mb-0">
      <?= htmlspecialchars($r["comment"]) ?>
    </p>

  </div>

<?php endforeach; ?>

  </div>

</div>

    `;

  } catch(error){

    console.error(error);
    container.innerHTML = "<p class='text-danger text-center'>Failed to load recipe.</p>";

  }

}


// =========================
// save recipe
// =========================
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

      button.textContent = "Saved";
      button.classList.remove("btn-warning");
      button.classList.add("btn-dark");
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
      button.textContent = "Saved";
      button.classList.remove("btn-warning");
      button.classList.add("btn-dark");
    } else {
      button.textContent = "Save Recipe";
      button.classList.remove("btn-dark");
      button.classList.add("btn-warning");
    }

  } catch (err) {
    console.error(err);
    alert("Something went wrong");
  }
}

// =========================
// RECENTLY VIEWED STORAGE
// =========================
function saveRecentlyViewed(mealId){
  let recent = JSON.parse(localStorage.getItem("recentRecipes")) || [];

  // Remove if already exists (avoid duplicates)
  recent = recent.filter(id => id !== mealId);

  // Add to start of array
  recent.unshift(mealId);

  // Limit to 10 items
  recent = recent.slice(0, 10);

  localStorage.setItem("recentRecipes", JSON.stringify(recent));
}

//reviews
async function submitReview(){

  const comment = document.getElementById("reviewText").value.trim();
  const feedback = document.getElementById("reviewFeedback");

  if(comment.length < 3){
    feedback.innerHTML = `<div class="alert alert-warning">Write something meaningful</div>`;
    return;
  }

  const res = await fetch("addReview.php", {
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:`meal_id=${mealId}&comment=${encodeURIComponent(comment)}`
  });

  const data = await res.json();

  if(data.success){

 const today = new Date().toLocaleDateString("en-GB", {
  day: "2-digit",
  month: "short",
  year: "numeric"
});

document.getElementById("reviewsList").insertAdjacentHTML("afterbegin", `
  <div class="card p-3 mb-2 border-dark">

    <div class="d-flex justify-content-between align-items-start">

      <strong>You</strong>

      <div class="text-end">
        <small class="text-muted d-block">${today}</small>

        <button onclick="deleteReview()" 
                class="btn btn-sm btn-link text-danger p-0 mt-1"
                title="Delete review">
          <i class="bi bi-trash"></i>
        </button>
      </div>

    </div>

    <p class="mt-2 mb-0">${comment}</p>

  </div>
`);

  document.getElementById("reviewText").value = "";
  feedback.innerHTML = `<div class="alert alert-success">Review added!</div>`;


  } else {
    feedback.innerHTML = `<div class="alert alert-warning">${data.message}</div>`;
  }
}

async function deleteReview(){

  if(!confirm("Delete your review?")) return;

  const res = await fetch("deleteReview.php", {
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:`meal_id=${mealId}`
  });

  const data = await res.json();

  if(data.success){
    location.reload(); // simple approach
  }
}

// Run when page loads
document.addEventListener("DOMContentLoaded", () => {
  if(mealId){
    saveRecentlyViewed(mealId);
  }
});

document.addEventListener("DOMContentLoaded", loadRecipe);
</script>
</body>
</html>