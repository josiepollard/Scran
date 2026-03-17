<?php
session_start();
include "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $email = $_POST["email"];
  $password = $_POST["password"];

  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {

      $_SESSION["user"] = $user["name"];

      header("Location: index.php");
      exit();

    } else {
      $message = "Wrong password.";
    }

  } else {
    $message = "No user found.";
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

<title>Login - SCRAN</title>
</head>

<body class="page-home">

<!-- NAVBAR -->
<?php include 'includes/navbar.php'; ?>

<section class="container d-flex align-items-center justify-content-center" style="min-height:80vh;">

  <div class="card shadow p-4" style="width:100%; max-width:400px;">

    <h2 class="text-center mb-4">Login</h2>

    <!-- MESSAGE -->
    <?php if($message): ?>
      <div class="alert alert-info text-center">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php">

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

      <button type="submit" class="btn btn-warning w-100">Login</button>

    </form>

    <p class="text-center mt-3">
      Don't have an account? <a href="signup.php">Sign up</a>
    </p>

  </div>

</section>

<!-- FOOTER -->
<?php include 'includes/footer.html'; ?>

</body>
</html>