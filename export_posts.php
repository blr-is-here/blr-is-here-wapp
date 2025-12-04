<?php
session_start();

// Check if user is logged in
if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true)) {
    header('Location: login.php');
    exit;
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filename'])) {
    // Clean and validate filename
    // $filename = filter_var($_POST['filename'], FILTER_SANITIZE_STRING);
    $filename = $_POST['filename'];
    
    // Add .txt extension if not present
    if (!str_ends_with(strtolower($filename), '.txt')) {
        $filename .= '.txt';
    }

    // Execute system command to log action in activities.log
    $user = $_SESSION['username'] ?? 'unknown_user';
    $log_command = 'echo "User \'' .$user . '\' exported posts on ' . date('Y-m-d H:i:s') . ' in filename '.$filename .'" >> activities.log';
    error_log("Executing command: " . $log_command); // Log the command for debugging
    shell_exec($log_command);

    // Connect to database
    include 'includes/header.php';

    // Get all blog posts
    $query = "SELECT title, content, created_at FROM blog_posts ORDER BY created_at DESC";
    $result = $mysqli->query($query);
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }

    // Create file content
    $output = "";
    foreach ($posts as $post) {
        $output .= "=== " . $post['title'] . " ===\n";
        $output .= "Date: " . $post['created_at'] . "\n\n";
        $output .= $post['content'] . "\n\n";
        $output .= "--------------------------------\n\n";
    }

    // Set download headers
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($output));

    // Send content
    echo $output;
    exit;
} else {
    // Redirect to admin page if accessed directly
    header('Location: admin.php');
    exit;
}
?>