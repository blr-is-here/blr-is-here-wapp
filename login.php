<?php
include 'includes/header.php';

// Check if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  echo '<script type="text/javascript">
          window.location.href="admin.php" 
        </script>';
  exit;
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if username and password are correct
  $username = $_POST['username'];
  $password = $_POST['password'];

  // filter inputs to prevent SQL injection
  $username = $mysqli->real_escape_string($username);
  // log username in console for debugging
  error_log("Login attempt for username: " . $username);

  // check if user exists
  $sql_check_user = "SELECT * FROM users WHERE username = '$username'";

  $result_check_user = $mysqli->query($sql_check_user);
  if (mysqli_num_rows($result_check_user) == 0) {
    $error = 'User does not exist, sorry !';
  } else {
    /*
     * Note from @dave : escape disabled to allow special characters in password
     * If anybody knows a better way to do it, please tell me 
     */
    // $password = $mysqli->real_escape_string($password);

    // Query to check if username and password match
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    try {
      $result = $mysqli->query($sql);
      if (!$result) {
        $error = 'Query failed: ' . $mysqli->error;
      }

      // Check if query was successful


      // Check if username and password match
      if (mysqli_num_rows($result) > 0) {
        // Store session data
        $_SESSION['loggedin'] = true;

        // Redirect to admin page
        echo '<script type="text/javascript">
          window.location.href="admin.php" 
        </script>';
        $error = 'Authentification successfull. You will be redirected to the admin page in 5 seconds.';
      } else {
        // Display error message
        $error = 'Invalid username or password';
      }
    } catch (Exception $e) {
      $error = 'Debug : Query error: ' . $e->getMessage() . 'Request : ' . $sql;
    }
  }
}
?>

<main>
  <section>
    <div class="container d-flex justify-content-center">
      <div class="row">
        <div class="col">
          <h2>Connect to access admin area</h2>
          <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
          <?php endif; ?>

          <form class='login-form' method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password"><br>

            <input type="submit" value="Login">
          </form>
        </div>
      </div>
    </div>
  </section>
</main>
<?php exit; ?>
<?php include 'includes/footer.php'; ?>