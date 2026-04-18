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
          <li><a href="meetTheTeam.php" class="footer-link">Meet the team</a></li>
        </ul>
      </div>

      <!-- Social Links -->
      <div class="col-md-4 mb-4">
        <h5>Follow Us</h5>
        <div class="d-flex gap-3 mt-3">
          <a href="https://www.facebook.com/UniversityOfWorcester" class="social-link" target="_blank">Facebook</a>
          <a href="https://www.instagram.com/worcester_uni/" class="social-link" target="_blank">Instagram</a>
         
        </div>
      </div>

      <!-- Theme + Text Size toggles -->
<div class="text-center mt-3 d-flex justify-content-center gap-2">
  <button id="themeToggle" class="btn btn-outline-secondary btn-sm">Dark Mode</button>
  <button id="textSizeToggle" class="btn btn-outline-secondary btn-sm">Large Text</button>
</div>

    <!-- Footer bottom -->
    <div class="text-center pt-4 border-top mt-4">
      <small>© 2026 SCRAN.</small>
    </div>
  </div>
</footer>

<script>

  (function(){
    const themeButton = document.getElementById("themeToggle");
    const textButton = document.getElementById("textSizeToggle");

    // Load saved preferences
    const savedTheme = localStorage.getItem("theme");
    const savedTextSize = localStorage.getItem("textSize");

    // Apply theme
    if(savedTheme === "dark"){
      document.body.classList.add("dark-mode");
      if(themeButton) themeButton.textContent = "Light Mode";
    }

    // Apply text size
    if(savedTextSize === "large"){
      document.body.classList.add("large-text");
      if(textButton) textButton.textContent = "Normal Text";
    }

    // Theme toggle
    if(themeButton){
      themeButton.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
        const isDark = document.body.classList.contains("dark-mode");
        localStorage.setItem("theme", isDark ? "dark" : "light");
        themeButton.textContent = isDark ? "Light Mode" : "Dark Mode";
      });
    }

    // Text size toggle
    if(textButton){
      textButton.addEventListener("click", () => {
        document.body.classList.toggle("large-text");
        const isLarge = document.body.classList.contains("large-text");
        localStorage.setItem("textSize", isLarge ? "large" : "normal");
        textButton.textContent = isLarge ? "Normal Text" : "Large Text";
      });
    }
  })();

</script>