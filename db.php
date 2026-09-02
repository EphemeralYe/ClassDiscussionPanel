<?php
$conn = mysqli_connect("localhost", "root", "", "cs_doubts");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>