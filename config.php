<?php
$conn = new mysqli("localhost", "root", "", "scran_users");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>