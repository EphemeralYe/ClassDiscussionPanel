<?php
session_start();
require 'db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['acc_password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $message = "Wrong email or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login - CS Doubts</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav><a class="brand" href="login.php">CS Doubts</a></nav>
<div class="wrap">
  <div class="card narrow">
    <h2>Login</h2>
    <?php if ($message) { ?>
      <p class="msg-err"><?php echo $message; ?></p>
    <?php } ?>
    <form method="POST">
      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <button type="submit">Login</button>
    </form>
    <p>No account? <a href="register.php">Register here</a></p>
  </div>
</div>
</body>
</html>