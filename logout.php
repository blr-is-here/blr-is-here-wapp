<?php
include 'includes/header.php';

// Check if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  session_unset();
  session_destroy();
  $message = "You have been successfully disconnected. You will be redirected to the homepage in 5 seconds.";
  echo '<script type="text/javascript">
          window.location.href="index.php" 
        </script>';
} else {
  $message = "You are not connected. You will be redirected to the homepage in 5 seconds.";
  echo '<script type="text/javascript">
          window.location.href="index.php" 
        </script>';
}
?>

<main>
  <section>
    <div class="container d-flex justify-content-center">
      <div class="row">
        <div class="col">
          <div class="disconnect-message"><?php echo $message; ?></div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php exit; ?> 
<?php include 'includes/footer.php'; ?>
