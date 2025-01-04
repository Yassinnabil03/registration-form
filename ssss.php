<?php
session_start();
include("php/confg.php");

// Redirect to login if not logged in
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit;
}

// Handle new post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $user_id = $_SESSION['id']; // User ID from the session
    $content = mysqli_real_escape_string($con, $_POST['content']);
    $file_path = null;

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; // Directory to store uploaded images
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $file_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $file_path = $target_file;
        } else {
            echo "Error uploading the image.";
        }
    }

    // Insert new post into the database
    $query = "INSERT INTO posts (user_id, content, image) VALUES ('$user_id', '$content', '$file_path')";
    if (mysqli_query($con, $query)) {
        $message = "Post saved successfully!";
    } else {
        $message = "Error: Could not save post. " . mysqli_error($con);
    }
}

// Fetch all posts by the logged-in user
$user_id = $_SESSION['id'];
$result = mysqli_query($con, "SELECT content, image, created_at FROM posts WHERE user_id='$user_id' ORDER BY created_at DESC");
$posts = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
} else {
    echo "Error: Could not fetch posts. " . mysqli_error($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="right-links">
        <a href="php/logout.php"><button class="btn">Log Out</button></a>
    </div>

    <main>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>

        <!-- Post creation form -->
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="content">Content:</label><br>
                <textarea name="content" id="content" rows="5" required></textarea>
            </div>
            <div>
                <label for="image">Upload Image:</label><br>
                <input type="file" name="image" id="image">
            </div>
            <button type="submit" name="save">Save Post</button>
        </form>

        <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Display user posts -->
        <h2>Your Posts</h2>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <p><strong>Content:</strong> <?php echo htmlspecialchars($post['content']); ?></p>
                    <?php if ($post['image']): ?>
                        <p><strong>Image:</strong><br>
                        <img src="<?php echo $post['image']; ?>" alt="Post Image" style="max-width: 200px;">
                    <?php endif; ?>
                    <p><small>Posted on: <?php echo $post['created_at']; ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts to display.</p>
        <?php endif; ?>
    </main>
</body>
</html>
