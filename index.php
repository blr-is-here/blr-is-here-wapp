<?php include 'includes/header.php'; ?>

<main>
  <section class="py-5">
    <div class="site-container container">
      <div class="row">
        <div class="col-12 col-lg-8">
          <section class="card p-4 mb-4">
            <h2 class="mb-3">Check out our latest article</h2>
            <?php
            // Query the database for the latest blog article
            $result = $mysqli->query("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 1");

            // Check for errors
            if (!$result) {
              echo "Failed to retrieve latest blog article: " . $mysqli->error;
              exit();
            }

            // Fetch the latest blog article as an associative array
            $latest_article = $result->fetch_assoc();

            // Output the latest blog article
            echo "<h5>" . $latest_article['title'] . "</h5>";
            echo "<p>" . $latest_article['content'] . "</p>";
            ?>
          </section>

        </div>

        <!-- Sidebar column -->
        <div class="col-12 col-lg-4">
          <aside class="card p-3">
            <?php
            // query the database for all categories
            $categories = $mysqli->query("SELECT * FROM categories");

            // Check for errors
            if (!$categories) {
              echo "Failed to retrieve categories: " . $mysqli->error;
              exit();
            }

            // Fetch all categories as an associative array
            $categories = $categories->fetch_all(MYSQLI_ASSOC);

            // Output the dropdown list
            echo '<form method="POST" action="blog.php">';
            echo '<label for="category">Choose a category:</label>';
            echo '<select name="category" id="category" class="form-select mb-2">';
            foreach ($categories as $category) {
              echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
            }
            echo '</select>';
            echo '<button class="btn btn-secondary w-100" type="submit">Submit</button>';
            echo '</form>';

            // Query the database for the latest blog article list
            $result = $mysqli->query("SELECT * FROM blog_posts ORDER BY created_at");
            // Check for errors
            if (!$result) {
              echo "Failed to retrieve latest blog article: " . $mysqli->error;
              exit();
            }

            ?>
          </aside>

          <!-- Additional sidebar blocks: recent articles, video, ad -->
          <div class="card p-3 mt-3">
            <h5>These articles can also interest you</h5>
            <?php
            // Output the latest blog articles
            echo "<ul>";
            while ($article = $result->fetch_assoc()) {
              echo "<li>" . $article['title'] . "</li>";
            }
            echo "</ul>";
            ?>
          </div>

          <div class="card p-3 mt-3">
            <h5>Check this video</h5>
            <div class="ratio ratio-16x9">
              <iframe src="https://www.youtube.com/embed/9bZkp7q19f0" title="YouTube video" allowfullscreen></iframe>
            </div>
          </div>

          <div class="card p-3 mt-3">
            <h5>Ad corner</h5>
            <?php
            if (isset($_GET['ad'])) {
              include 'advertising/' . $_GET['ad'];
            } else {
              include 'advertising/default.php';
            }
            ?>
            <a href="?ad=ad-qwant.php" class="btn btn-primary mt-3 w-100">Hide boring ad</a>
          </div>

        </div> <!-- .col sidebar -->
      </div> <!-- .row -->
    </div> <!-- .site-container -->
  </section>

</main>
<?php include 'includes/footer.php'; ?>