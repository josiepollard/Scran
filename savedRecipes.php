<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <link rel="stylesheet" type="text/css" href="styles/home.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Scran</title>
    <link rel="icon" type="image/x-icon" href="/images/favi.png">

</head>

<body>

<!-- NAVBAR START -->
<!-- uses includes/navbar.php which we will reuse throughout the website-->
<?php include 'includes/navbar.html'; ?>
<!-- NAVBAR END -->


<!-- BANNER START -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>

  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">Saved Recipes</h1>
  </div>
</section>
<!-- BANNER END -->

<!-- FOOTER START -->
<?php include 'includes/footer.html'; ?>
<!-- FOOTER END -->


</body>
</html>