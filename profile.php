<?php
include "config.php";

if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styles/index.css">
<title>Profile</title>
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="container my-5" style="max-width:600px;">

  <h2 class="mb-4">Profile Settings</h2>

  <!-- CHANGE NAME -->
  <div class="card p-3 mb-4">
    <h5>Change Name</h5>

    <input type="text" id="nameInput" class="form-control mb-2"
      value="<?= htmlspecialchars($_SESSION["user_name"]) ?>">

    <button onclick="updateName()" class="btn btn-dark">Update Name</button>
  </div>

  <!-- CHANGE PASSWORD -->
  <div class="card p-3 mb-4">
    <h5>Change Password</h5>

    <input type="password" id="currentPassword" class="form-control mb-2" placeholder="Current password">
    <input type="password" id="newPassword" class="form-control mb-2" placeholder="New password">

    <button onclick="changePassword()" class="btn btn-dark">Update Password</button>
  </div>

  <!-- DELETE ACCOUNT -->
  <div class="card p-3 border-danger">
    <h5 class="text-danger">Delete Account</h5>

    <p>This action cannot be undone.</p>

    <button onclick="deleteAccount()" class="btn btn-danger">
      Delete Account
    </button>
  </div>


</div>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<script>
async function updateName(){

  const name = document.getElementById("nameInput").value;

  const res = await fetch("updateProfile.php", {
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:"name=" + encodeURIComponent(name)
  });

  const data = await res.json();

  if(data.success){
    alert("Name updated!");
    location.reload();
  } else {
    alert("Error updating name");
  }
}

async function changePassword(){

  const current = document.getElementById("currentPassword").value;
  const newPass = document.getElementById("newPassword").value;

  const res = await fetch("changePassword.php", {
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:`current=${current}&new=${newPass}`
  });

  const data = await res.json();

  alert(data.message);
}

async function deleteAccount(){

  if(!confirm("Are you sure you want to delete your account?")){
    return;
  }

  const res = await fetch("deleteAccount.php", { method:"POST" });
  const data = await res.json();

  if(data.success){
    window.location.href = "index.php";
  } else {
    alert("Error deleting account");
  }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>