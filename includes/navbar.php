<!-- NAV START -->
<nav class="navbar navbar-expand-lg navbar-dark scran-nav sticky-top">
  <div class="container-fluid px-3 px-lg-4">

    <a class="navbar-brand" href="index.html">
      <span class="scran-logo">SCRAN.</span>
    </a>

    <!-- Mobile toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#scranNavbar" aria-controls="scranNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible content -->
    <div class="collapse navbar-collapse" id="scranNavbar">
      <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 gap-lg-2 scran-links">
        <li class="nav-item">
          <a class="nav-link"  href="index.php">Home</a> 

       <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" 
            href="#" 
            id="recipesDropdown"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false">
            Recipes
        </a>

        <ul class="dropdown-menu scran-dropdown">
            <li><a class="dropdown-item" href="#">All Recipes</a></li>
            <li><a class="dropdown-item" href="#">Breakfast</a></li>
            <li><a class="dropdown-item" href="#">Lunch</a></li>
            <li><a class="dropdown-item" href="#">Dinner</a></li>
            <li><a class="dropdown-item" href="#">Desserts</a></li>
            <li><a class="dropdown-item" href="#">Snacks</a></li>
        </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">Whats in your cupboard?</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Scrandom!</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Saved recipes</a>
        </li>
      </ul>

      <div class="d-flex gap-2 align-items-center ms-lg-3 scran-user-btns">
        <a class="btn btn-light scran-btn" href="#">Login</a>
        <a class="btn btn-outline-light scran-btn" href="#">Sign up</a>
      </div>
    </div>
  </div>
</nav>
<!-- NAV END -->