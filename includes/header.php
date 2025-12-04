<?php
if (!isset($_SESSION)) {
  session_start();
}
// Connect to the database
$DB_HOST = $_ENV['DB_HOST'] ?? 'localhost:3306';
// log DB_HOST in console for debugging
error_log("DB_HOST: " . $DB_HOST);

$mysqli = new mysqli($DB_HOST, "john", "password123", "weakApp");

// Check for errors
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>W34K4PP</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="main.css">
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="/">WeakApp</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="/">Home</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="/blog.php">blog</a>
          </li>
          <li class="nav-item">
            <?php
            // check if the user is logged in
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
              // user is logged in, show the link
              echo '<a class="nav-link" href="/admin.php">Protected Section</a>';
            } else {
              // user is not logged in, disable the link
              echo '<a class="nav-link disabled" href="#">Protected Section</a>';
            }
            ?>
          </li>
        </ul>
      </div>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <?php
            // check if the user is logged in
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
              // user is logged in, show the link
              echo '<a class="nav-link btn btn-primary" href="/logout.php">Disconnect</a>';
            } else {
              // user is not logged in, disable the link
              echo '<a class="nav-link btn btn-primary" href="/login.php">Login</a>';
            }
            ?>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Site container (wraps page content) -->
  <div class="site-container container py-5">