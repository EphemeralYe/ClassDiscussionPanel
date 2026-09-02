<?php
session_start();
require 'db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'] == 'teacher' ? 'teacher' : 'student';

    $sql = "INSERT INTO users (username, email, acc_password, role) VALUES ('$username', '$email', '$password', '$role')";

    if (mysqli_query($conn, $sql)) {
        header("Location: login.php");
        exit;
    } else {
        $message = "Email already used. Try another.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register - CS Doubts</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav><a class="brand" href="login.php">CS Doubts</a></nav>
<div class="wrap">
  <div class="card narrow">
    <h2>Register</h2>
    <?php if ($message) { ?>
      <p class="msg-err"><?php echo $message; ?></p>
    <?php } ?>
    <form method="POST">
      <label>Username</label>
      <input type="text" name="username" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <label>I am a</label>
      <select name="role">
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
      </select>

      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</div>
</body>
</html>