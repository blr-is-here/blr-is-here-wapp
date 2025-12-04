<?php
include 'includes/header.php';

// Connect to the database
if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true)) {
  echo '<script type="text/javascript">
          window.location.href="login.php" 
        </script>';
  exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validate input data
  $title = $_POST['title'];
  $content = $_POST['content'];
  $image = $_FILES['image'];



  // XSS prevention : block if title or content contains <script> tags or "on*" attributes
  if (
    strpos($title, '<script>') !== false || preg_match('/on\w+\s*=/i', $title) ||
    strpos($title, '</script>') !== false || preg_match('/on\w+\s*=/i', $content)
  ) {
    $error = 'Hacking detected. Please remove any <script> tags or event handler attributes.';
  }
  // Check if required fields are empty
  if (empty($title) || empty($content) || empty($image)) {
    $error = 'Please fill in all required fields.';
  }

  // Check if image is valid
  // if (!in_array($image['type'], ['image/jpeg', 'image/png'])) {
  //   $error = 'Please upload a valid image file (JPEG or PNG).';
  // }

  // Check if image size is less than 2MB
  if ($image['size'] > 2 * 1024 * 1024) {
    $error = 'Image size must be less than 2MB.';
  }

  // Check if image is not php extension
  $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
  if (strtolower($ext) === 'php') {
    $error = 'PHP files are not allowed.';
  }

  // If there are no errors, upload image and save post data to database
  if (!isset($error)) {
    $imagePath = './uploads/' . $image['name'];
    move_uploaded_file($image['tmp_name'], $imagePath);

    // Prepare and execute SQL query to insert post data
    /*
     * Note form @dave : I dont know how to link image and post... So I skip it for now.
     * But image upload works fine :)
     */
    $author_id = 1;
    $category_id = 1; // Assuming category_id is 1 for now
    $stmt = $mysqli->prepare("INSERT INTO blog_posts (title, content, author_id, category_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $content, $author_id, $category_id);
    $stmt->execute();

    $success = 'Post creation OK ! Image uploaded successfully in /uploads/' . htmlspecialchars($image['name']);
  }
}

// Display form
?>
<main>
  <section>

    <div class="container d-flex flex-column">
      <h1>Admin Panel</h1>


      <h2>Add New Blog Post</h2>
      <div class="container">
        <div class="row">
          <div class="col">
            <form class="admin-form-post" method="post" enctype="multipart/form-data">
              <label for="title">Title:</label>
              <input type="text" name="title" id="title" required>

              <label for="content">Content:</label>
              <textarea name="content" id="content" required></textarea>

              <label for="image">Image:</label>
              <input type="file" name="image" id="image" accept="image/*">

              <button type="submit">Add Post</button>
            </form>
            <!-- Display success or error after add post -->
            <div>
              <?php
              if (isset($success)): ?>
                <div class="alert alert-success mt-3" role="alert">
                  <strong>Succès !</strong> <?= htmlspecialchars($success) ?>
                </div>
              <?php elseif (isset($error)): ?>
                <div class="alert alert-danger mt-3" role="alert">
                  <strong>Erreur :</strong> <?= htmlspecialchars($error) ?>
                </div>
              <?php endif; ?>
              ?>
            </div>
          </div>
        </div>
      </div>

      <div class="container mt-4">
        <h2>Export Blog Posts in File</h2>
        <div class="row">
          <div class="col">
            <form class="admin-form-export" method="post" action="export_posts.php">
              <label for="filename">Target filename : </label>
              <input type="text" name="filename" id="filename" required placeholder="export.txt">
              <button type="submit" name="export">Export Posts</button>
            </form>
          </div>
        </div>
      </div>
  </section>
</main>

<?php
