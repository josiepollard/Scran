<!-- 
 NAVBAR
 * reused across pages
 * contains logo and links 
 * Conditional links depending on user logged in
    - if logged in, show user profile page link and logout button
    - if not logged in, show login and sign up buttons
 -->


<?php
//Session start, start if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark scran-nav sticky-top">
  <div class="container-fluid px-3 px-lg-4">

    <!-- Logo -->
    <!-- clicking logo goes to homepage -->
    <a class="navbar-brand" href="index.php">
      <span class="scran-logo">SCRAN.</span>
    </a>

    <!-- Toggle -->
    <!-- Nav bar replaced with collapsable menu on small screen -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#scranNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav -->
  <div class="collapse navbar-collapse" id="scranNavbar">

  <!-- Links (centered ones) -->
  <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 gap-lg-2 scran-links">

    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>

    <!-- Recipes dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="recipesDropdown" role="button" data-bs-toggle="dropdown">
        Recipes
      </a>

      <!-- dropdown child links -->
      <ul class="dropdown-menu scran-dropdown">
        <li><a class="dropdown-item" href="recipes.php">All Recipes</a></li>
        <li><a class="dropdown-item" href="breakfast.php">Breakfast</a></li>
        <li><a class="dropdown-item" href="desserts.php">Desserts</a></li>
        <li><a class="dropdown-item" href="starter.php">Starters</a></li>
        <li><a class="dropdown-item" href="side.php">Side Dishes</a></li>
        <li><a class="dropdown-item" href="vegetarian.php">Vegetarian</a></li>
        <li><a class="dropdown-item" href="vegan.php">Vegan</a></li>
      </ul>
    </li>

    <li class="nav-item"><a class="nav-link" href="whatsInYourCupboard.php">What's in your cupboard?</a></li>
    <li class="nav-item"><a class="nav-link" href="scrandom.php">Scrandom!</a></li>
    <li class="nav-item"><a class="nav-link" href="savedRecipes.php">Saved recipes</a></li>
  </ul>

      <!-- Right hand side, user login & Profile settings link -->
      <div class="d-flex gap-2 align-items-center ms-lg-3 scran-user-btns">

      <!--if user is logged in -->
      <?php if (isset($_SESSION["user_name"])): ?> 

      <!-- dropdown menu -->
      <div class="dropdown">
        <a class="nav-link dropdown-toggle text-white"
          href="#"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false">

        <!-- Shows users name -->
        <!-- htmlspecialchars used to prevent XSS -->
          Hi, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
          <li>
          <!-- Link to profile settings page-->
            <a class="dropdown-item" href="profile.php">
              Profile Settings
            </a>
          </li>

          <li>
             <!-- logout button-->
            <a class="dropdown-item text-danger" href="logout.php">
              Logout
            </a>
          </li>
        </ul>
      </div>

<!-- not logged in -->
<?php else: ?>

  <a class="btn btn-light scran-btn" href="login.php">Login</a>
  <a class="btn btn-outline-light scran-btn" href="signup.php">Sign Up</a>

<?php endif; ?>

      </div>

    </div>
  </div>
</nav>