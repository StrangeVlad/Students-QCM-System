<?php
session_start();

$userLogin = $passLogin = "";

include_once "../../Resource/db.php";

if (isset($_POST['btn'])) {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
      $userLogin = "username is required";
    }

    if (empty($password)) {
      $passLogin = "Password is required";
    }

    if (empty($userLogin) && empty($passLogin)) {
      $sql = "SELECT * FROM teachers WHERE username = '$username'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
          header("Location: ../index.php");
          exit();
        } else {
          $passLogin = "Incorrect password";
        }
      } else {
        $userLogin = "username not found";
      }
    }
  }
}

$conn->close();
