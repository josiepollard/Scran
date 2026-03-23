<!-- 
 login page
 * If user is already logged in, they will be redirected to homepage
 * If user is not logged in, they can enter their email and password to log in
 -->

<?php
include "config.php"; // database connection

$message = ""; // holds error/success messages

// Start session to check if user is already logged in
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Check if both email and password are provided
    if ($email === "" || $password === "") {
        $message = "Please enter your email and password.";

        // If either field is empty, show an error message
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_email"] = $user["email"];

                header("Location: index.php");
                exit();
            } else {
                $message = "Wrong password.";
            }
        } else {
            $message = "No user found with that email.";
        }

        $stmt->close();
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
  <title>SCRAN | Login</title>
</head>
<body class="page-home">

<!-- Navbar -->
<?php include 'includes/navbar.php'; ?>

<!-- login section-->
<section class="container d-flex align-items-center justify-content-center" style="min-height:80vh;">
  <div class="card shadow p-4" style="width:100%; max-width:400px;">
    <h2 class="text-center mb-4">Login</h2>

    <!-- Show any messages -->
    <?php if ($message): ?>
      <div class="alert alert-info text-center"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- form -->
    <form method="POST" action="login.php">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

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

<!-- footer-->
<?php include 'includes/footer.php'; ?>
</body>
</html>