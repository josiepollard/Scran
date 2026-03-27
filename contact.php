<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="styles/index.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<title>SCRAN | Contact</title>
<link rel="icon" type="image/x-icon" href="/images/favi.png">

</head>

<body class="page-contact">

<!-- navbar -->
<?php include 'includes/navbar.php'; ?>

<!-- Banner -->
<section class="scran-hero-main d-flex align-items-center justify-content-center text-center">
  <div class="scran-hero-main-overlay"></div>
  <div class="scran-hero-main-content container">
    <h1 class="scran-hero-main-title">Contact</h1>
  </div>
</section>

<div class="contactForm">
                        <form>
                            <h2>Send Message</h2>
                            <div class="inputBox">
                                <input type="text" name="" required="required">
                                <span>Full Name</span>
                            </div>

                            <div class="inputBox">
                                <input type="email" name="" required="required">
                                <span>Email</span>
                            </div>

                            <div class="inputBox">
                                <textarea required="required"></textarea>
                                <span>Type Your Message...</span>
                            </div>

                            <div class="inputBox">
                                <input type="submit" name="" value="Send">
                            </div>
                        </form>
                    </div>

<!-- footer -->
<?php include 'includes/footer.php'; ?>

</body>
</html>