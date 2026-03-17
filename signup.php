
<?php
session_start();
include "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm = $_POST["confirm"];

  if ($password !== $confirm) {
    $message = "Passwords do not match.";
  } else {

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$hashed')";

    if ($conn->query($sql)) {
      $message = "Account created! You can now login.";
    } else {
      $message = "Email already exists.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styles/index.css">

<title>Sign Up - SCRAN</title>
</head>

<body class="page-home">

<!-- NAVBAR -->
<?php include 'includes/navbar.php'; ?>

<section class="container d-flex align-items-center justify-content-center" style="min-height:80vh;">

  <div class="card shadow p-4" style="width:100%; max-width:400px;">

    <h2 class="text-center mb-4">Create Account</h2>

<?php if($message): ?>
  <div class="alert alert-info text-center">
    <?php echo $message; ?>
  </div>
<?php endif; ?>

    <form method="POST" action="signup.php">

      <!-- NAME -->
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <!-- EMAIL -->
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <!-- PASSWORD -->
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <!-- CONFIRM -->
      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-warning w-100">Sign Up</button>

    </form>

    <p class="text-center mt-3">
      Already have an account? <a href="login.php">Login</a>
    </p>

  </div>

</section>

<?php include 'includes/footer.html'; ?>

</body>
</html>