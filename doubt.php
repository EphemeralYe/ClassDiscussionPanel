<?php
session_start();
if (!isset($_SESSION['id'])) { header("Location: login.php"); exit; }
require 'db.php';

$id = (int) $_GET['id'];

// post an answer
if (isset($_POST['post_answer'])) {
    $answer_text = mysqli_real_escape_string($conn, $_POST['answer_text']);
    $user_id = $_SESSION['id'];
    mysqli_query($conn, "INSERT INTO answers (doubt_id, user_id, answer_text) VALUES ('$id', '$user_id', '$answer_text')");
    header("Location: doubt.php?id=$id");
    exit;
}

// mark as solved (only the poster can do this)
if (isset($_POST['mark_solved'])) {
    mysqli_query($conn, "UPDATE doubts SET status = 'solved' WHERE id = '$id' AND user_id = '{$_SESSION['id']}'");
    header("Location: doubt.php?id=$id");
    exit;
}

$result = mysqli_query($conn, "SELECT doubts.*, users.username FROM doubts JOIN users ON doubts.user_id = users.id WHERE doubts.id = '$id'");
$doubt = mysqli_fetch_assoc($result);

$answers = mysqli_query($conn, "SELECT answers.*, users.username, users.role FROM answers JOIN users ON answers.user_id = users.id WHERE doubt_id = '$id' ORDER BY answers.id ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $doubt['title']; ?> - CS Doubts</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  <a class="brand" href="index.php">CS Doubts</a>
  <div>
    <span>Hi, <?php echo $_SESSION['username']; ?></span>
    <a href="logout.php">Logout</a>
  </div>
</nav>

<div class="wrap">
  <a href="index.php">&larr; Back to all doubts</a>

  <div class="card doubt-row <?php if ($doubt['status'] == 'solved') echo 'solved'; ?>">
    <span class="badge topic"><?php echo $doubt['subject']; ?></span>
    <span class="badge <?php echo $doubt['status'] == 'Not Solved' ? 'not' : 'solved'; ?>"><?php echo strtoupper($doubt['status']); ?></span>

    <h2><?php echo $doubt['title']; ?></h2>
    <p><?php echo nl2br($doubt['description']); ?></p>
    <small>Posted by <?php echo $doubt['username']; ?></small>

    <?php if ($doubt['user_id'] == $_SESSION['id'] && $doubt['status'] == 'Not Solved') { ?>
      <form method="POST">
        <button type="submit" name="mark_solved" class="btn-green">Mark as Solved</button>
      </form>
    <?php } ?>
  </div>

  <div class="card">
    <h2>Answers</h2>

    <?php if (mysqli_num_rows($answers) == 0) { ?>
      <p>No answers yet.</p>
    <?php } ?>

    <?php while ($a = mysqli_fetch_assoc($answers)) { ?>
      <div class="answer">
        <p><?php echo nl2br($a['answer_text']); ?></p>
        <small>
          By <?php echo $a['username']; ?>
          <?php if ($a['role'] == 'teacher') { ?><span class="badge teacher-badge">TEACHER</span><?php } ?>
        </small>
      </div>
    <?php } ?>

    <form method="POST">
      <label>Your Answer</label>
      <textarea name="answer_text" rows="3" required></textarea>
      <button type="submit" name="post_answer">Submit Answer</button>
    </form>
  </div>
</div>
</body>
</html>