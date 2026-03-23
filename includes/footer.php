<!-- 
 FOOTER 
 * reused across pages
 * contains logo, links and theme toggle button
 -->

<footer class="scran-footer text-white mt-5">
  <div class="container py-5">
    <div class="row"> 

      <!-- Logo Section -->
      <div class="col-md-4 mb-4">
        <h4 class="scran-logo">SCRAN.</h4>
        <p>With SCRAN, you CAN!</p>
      </div>

      <!-- Navigation Links -->
      <div class="col-md-4 mb-4">
        <h5>Explore</h5>
        <ul class="list-unstyled">
          <li><a href="recipes.php" class="footer-link">All Recipes</a></li>
          <li><a href="contact.php" class="footer-link">Contact</a></li>
          <li><a href="meetTheTeam.php" class="footer-link">Meet The Team</a></li>
        </ul>
      </div>

      <!-- Social Links -->
      <div class="col-md-4 mb-4">
        <h5>Follow Us</h5>
        <div class="d-flex gap-3 mt-3">
          <a href="https://www.facebook.com/" class="social-link">Facebook</a>
          <a href="https://www.instagram.com/" class="social-link">Instagram</a>
          <a href="https://x.com/" class="social-link">X</a>
        </div>
      </div>

      <!-- Theme toggle -->
      <!-- allows switch between light and dark mode. light by default. -->
      <div class="text-center mt-3">
        <button id="themeToggle" class="btn btn-outline-secondary btn-sm">Dark Mode</button>
      </div>
    </div>

    <!-- Footer bottom -->
    <div class="text-center pt-4 border-top mt-4">
      <small>© 2026 SCRAN. All rights reserved.</small>
    </div>
  </div>
</footer>

<script>
(function(){
  const button = document.getElementById("themeToggle"); //Gets reference to toggle button

  // Apply saved theme on page load, saved in local storage
  const savedTheme = localStorage.getItem("theme");

  // if saved theme is dark, apply dark mode
  if(savedTheme === "dark"){
    document.body.classList.add("dark-mode");

    // update button text
    if(button) button.textContent = "Light Mode";
  }

  // Toggle click for theme choice
  if(button){ //only run if the button is present on page, error prevention 
    button.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode"); //Apply/disable dark-mode class
      const isDark = document.body.classList.contains("dark-mode"); //checks if dark mode is active
      localStorage.setItem("theme", isDark ? "dark" : "light"); //save users theme choice
      button.textContent = isDark ? "Light Mode" : "Dark Mode"; //update button text depending on current theme
    });
  }
})();
</script>