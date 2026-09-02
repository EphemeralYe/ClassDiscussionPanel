<?php
session_start();
if (!isset($_SESSION['id'])) { header("Location: login.php"); exit; }
require 'db.php';

// post a new doubt
if (isset($_POST['post_doubt'])) {
    $subject = $_POST['subject'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user_id = $_SESSION['id'];

    $subject = mysqli_real_escape_string($conn, $subject);
    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);

    $sql = "INSERT INTO doubts (user_id, subject, title, description) VALUES ('$user_id', '$subject', '$title', '$description')";
    mysqli_query($conn, $sql);
    header("Location: index.php");
    exit;
}

// filter by subject
$filter = "";
if (isset($_GET['subject'])) {
    $filter = mysqli_real_escape_string($conn, $_GET['subject']);
}

$sql = "SELECT doubts.*, users.username FROM doubts JOIN users ON doubts.user_id = users.id";
if ($filter != "") {
    $sql .= " WHERE subject = '$filter'";
}
$sql .= " ORDER BY doubts.id DESC";
$doubts = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>CS Doubts</title>
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

  <div class="card">
    <h2>Ask a Doubt</h2>
    <form method="POST">
      <label>Subject</label>
      <select name="subject" required>
        <option value="Python">Python</option>
        <option value="C">C</option>
        <option value="JavaScript">JavaScript</option>
        <option value="Java">Java</option>
        <option value="HTML/CSS">HTML/CSS</option>
        <option value="SQL">SQL</option>
        <option value="Other">Other</option>
      </select>

      <label>Title</label>
      <input type="text" name="title" required>

      <label>Description</label>
      <textarea name="description" rows="4" required></textarea>

      <button type="submit" name="post_doubt">Post Doubt</button>
    </form>
  </div>

  <div class="card">
    <form method="GET">
      <label>Filter by Subject</label>
      <select name="subject" onchange="this.form.submit()">
        <option value="">All Subjects</option>
        <option value="Python">Python</option>
        <option value="C">C</option>
        <option value="JavaScript">JavaScript</option>
        <option value="Java">Java</option>
        <option value="HTML/CSS">HTML/CSS</option>
        <option value="SQL">SQL</option>
        <option value="Other">Other</option>
      </select>
    </form>
  </div>

  <div class="card">
    <h2>All Doubts</h2>

    <?php if (mysqli_num_rows($doubts) == 0) { ?>
      <p>No doubts posted yet.</p>
    <?php } ?>

    <?php while ($row = mysqli_fetch_assoc($doubts)) { ?>
      <div class="card doubt-row <?php if ($row['status'] == 'solved') echo 'solved'; ?>">
        <span class="badge topic"><?php echo $row['subject']; ?></span>
        <span class="badge <?php echo $row['status'] == 'Not Solved' ? 'not' : 'solved'; ?>"><?php echo strtoupper($row['status']); ?></span>

        <h3><a href="doubt.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></h3>
        <small>Posted by <?php echo $row['username']; ?></small>
      </div>
    <?php } ?>
  </div>

</div>
</body>
</html>