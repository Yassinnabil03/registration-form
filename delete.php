<?php
session_start();
include("php/confg.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['valid'])) {
    header("Location: home.php");
    exit;
}

$email = $_SESSION['valid'];
$user_result = mysqli_query($con, "SELECT Id FROM users WHERE Email='$email'");
if (!$user_result) {
    die("User query failed: " . mysqli_error($con));
}
$user_row = mysqli_fetch_assoc($user_result);
$userid = $user_row['Id'];

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $check_query = "SELECT id FROM user_uploads WHERE user_id = $userid AND id = $id";
    $check_result = mysqli_query($con, $check_query);
    if (!$check_result) {
        die("Check query failed: " . mysqli_error($con));
    }
    if (mysqli_num_rows($check_result) === 0) {
        die("You are not authorized to delete this post.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        $delete_query = "DELETE FROM user_uploads WHERE user_id = $userid AND id = $id";
        $delete_result = mysqli_query($con, $delete_query);
        if (!$delete_result) {
            die("Delete query failed: " . mysqli_error($con));
        }
        header('Location: home.php?message=Post deleted successfully'); 
        exit;
    } else {
        $post_query = "SELECT description, file_path FROM user_uploads WHERE user_id = $userid AND id = $id";
        $post_result = mysqli_query($con, $post_query);
        if (!$post_result) {
            die("Post data query failed: " . mysqli_error($con));
        }
        $post = mysqli_fetch_assoc($post_result);
    }
} else {
    die("Invalid URL");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:#aaaaaa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .cancel-btn {
            background-color: #808080;
            color: white;
            border: none;
        }
        .btn:hover{
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Post</h1>
        <?php if (isset($post)): ?>
            <p>Are you sure you want to delete this post?</p>
            <?php if (!empty($post['file_path'])): ?>
                <img src="<?php echo htmlspecialchars($post['file_path']); ?>" alt="Post Image">
            <?php endif; ?>
            <p><?php echo htmlspecialchars($post['description']); ?></p>

            <form method="post">
                <button type="submit" name="confirm_delete" class="btn delete-btn">Delete</button>
                <a href="home.php" class="btn cancel-btn">Cancel</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>