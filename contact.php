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

<!-- form -->
<div class="container my-5" style="max-width:600px;">

  <h2 class="mb-4">Contact Us</h2>

  <div id="feedback"></div>

  <form id="contactForm">
    <input type="text" id="name" class="form-control mb-3" placeholder="Your name">
    <input type="email" id="email" class="form-control mb-3" placeholder="Your email">
    <textarea id="message" class="form-control mb-3" rows="5" placeholder="Your message"></textarea>
    <button type="submit" class="btn btn-dark w-100">Send Message</button>
  </form>
</div>

<!-- footer -->
<?php include 'includes/footer.php'; ?>

<script>
    // Handle form submission
    document.getElementById("contactForm").addEventListener("submit", async function(e){

    e.preventDefault();  // prevent page reload

    // Get input values
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const message = document.getElementById("message").value.trim();

    const feedback = document.getElementById("feedback"); 

    //validation to check if all fields are filled
    if(!name || !email || !message){
        feedback.innerHTML = `<div class="alert alert-warning">All fields are required</div>`;
        return;
    }

    // Send form data to server 
    const res = await fetch("submitContact.php", {
        method: "POST",
        headers: {
        "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&message=${encodeURIComponent(message)}`
    });

    const data = await res.json();

    // Show success or error message based on response
    if(data.success){
        feedback.innerHTML = `<div class="alert alert-success">Message sent successfully!</div>`;
        document.getElementById("contactForm").reset();
    } else {
        feedback.innerHTML = `<div class="alert alert-danger">Failed to send message</div>`;
    }

    });
</script>


</body>
</html>