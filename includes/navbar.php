

<?php
// ❌ REMOVE session_start() from here

if (!isset($_SESSION)) {
    // optional safety fallback, but usually not needed
}
?>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark scran-nav sticky-top">
  <div class="container-fluid px-3 px-lg-4">

    <!-- LOGO -->
    <a class="navbar-brand" href="index.php">
      <span class="scran-logo">SCRAN.</span>
    </a>

    <!-- MOBILE TOGGLE -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#scranNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- NAV CONTENT -->
    <div class="collapse navbar-collapse" id="scranNavbar">

      <!-- CENTER LINKS -->
      <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 gap-lg-2 scran-links">

        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>

        <!-- DROPDOWN -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle"
             href="#"
             id="recipesDropdown"
             role="button"
             data-bs-toggle="dropdown">
            Recipes
          </a>

          <ul class="dropdown-menu scran-dropdown">
            <li><a class="dropdown-item" href="recipes.php">All Recipes</a></li>
            <li><a class="dropdown-item" href="breakfast.php">Breakfast</a></li>
            <li><a class="dropdown-item" href="desserts.php">Desserts</a></li>
            <li><a class="dropdown-item" href="Starter.php">Starters</a></li>
            <li><a class="dropdown-item" href="Side.php">Side Dishes</a></li>
            <li><a class="dropdown-item" href="Vegetarian.php">Vegetarian</a></li>
            <li><a class="dropdown-item" href="Vegan.php">Vegan</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="whatsInYourCupboard.php">What's in your cupboard?</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="scrandom.php">Scrandom!</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="savedRecipes.php">Saved recipes</a>
        </li>

      </ul>

      <!-- RIGHT SIDE (LOGIN / USER) -->
      <div class="d-flex gap-2 align-items-center ms-lg-3 scran-user-btns">

        <?php if (isset($_SESSION["user_name"])): ?>
  <span class="nav-link text-white">Hi, <?php echo htmlspecialchars($_SESSION["user_name"]); ?></span>
  <a href="logout.php" class="btn btn-outline-light scran-btn">Logout</a>
<?php else: ?>
  <a class="btn btn-light scran-btn" href="login.php">Login</a>
  <a class="btn btn-outline-light scran-btn" href="signup.php">Sign Up</a>
<?php endif; ?>

      </div>

    </div>
  </div>
</nav>