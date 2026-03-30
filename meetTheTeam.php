<!-- 
 meet the team page, where users can learn about the team behind the project and their roles.
 -->
 
 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="styles/index.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <title>SCRAN | Meet the Team</title>
  <link rel="icon" type="image/x-icon" href="/images/favi.png">
</head>

<body class="page-meetTheTeam">

<!-- NAVBAR -->
<?php include 'includes/navbar.php'; ?>

<!-- Banner -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>
  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">Meet the Team</h1>
  </div>
</section>

<!-- ABOUT + IMAGE SIDE BY SIDE -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center">
      
      <!-- Text -->
      <div class="col-md-6">
        <p class="lead">
          SCRAN is a project designed to make discovering recipes simple and accessible for everyone.<br><br>

          We are a team of students working collaboratively to design and develop this website as part of our coursework. 
          Each member of the team has contributed their skills across planning, design, development, and research to bring SCRAN to life.
        </p>
      </div>

      <!-- Image -->
      <div class="col-md-6 text-center">
        <img src="images/theTeam.jpeg" alt="Team photo" class="img-fluid rounded">
      </div>

    </div>
  </div>
</section>


<!-- TEAM SECTION -->
<section class="team-section py-5">
  <div class="container">
    <div class="row g-4">

      <div class="col-md-6 col-lg-3">
        <div class="team-card text-center">
          <img src="images/kay.png" class="img-fluid rounded-circle mb-3" alt="Kay Photo">
          <h4>Kay Wesley</h4>
          <p class="text-muted">Project Manager & Developer </p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="team-card text-center">
          <img src="images/placeholder.png" class="img-fluid rounded-circle mb-3" alt="Member 4">
          <h4>Mia Gardner-Smith</h4>
          <p class="text-muted">Systems Analyst & Business Analyst </p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="team-card text-center">
          <img src="images/josie.png" class="img-fluid rounded-circle mb-3" alt="Josie Photo">
          <h4>Josie Pollard</h4>
          <p class="text-muted">Developer & Researcher </p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="team-card text-center">
          <img src="images/callum.png" class="img-fluid rounded-circle mb-3" alt="Callum Photo">
          <h4>Callum Sealy</h4>
          <p class="text-muted">Developer & Researcher</p>
        </div>
      </div>

    </div>
  </div>
</section>




<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>


</body>
</html>