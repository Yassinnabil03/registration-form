<?php
session_start();
include("php/confg.php");
if (!isset($_SESSION['valid'])) {
    header("Location: home.php");
    exit;
}

$email = $_SESSION['valid'];
$result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email'") or die("Select error");
$row = mysqli_fetch_assoc($result);

$userid = $row['Id'];

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $query = "SELECT * FROM user_uploads WHERE user_id = $userid AND id = $id";
    $result = mysqli_query($con, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        die("No post found for this user.");
    }

    $post = mysqli_fetch_assoc($result);
} else {
    die("Invalid URL");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_text = mysqli_real_escape_string($con, $_POST['description']);

    if (!empty($_FILES['file_path']['name'])) {
        $image_name = basename($_FILES['file_path']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['file_path']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            $image_path = $post['file_path']; 
        }
    } else {
        $image_path = $post['file_path']; 
    }

    $update_query = "UPDATE user_uploads SET description = '$post_text', file_path = '$image_path' WHERE user_id = $userid AND id = $id";
    if (mysqli_query($con, $update_query)) {
        header('Location: home.php?message=Post updated successfully');
        exit;
    } else {
        die("Error updating record: " . mysqli_error($con));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #aaaaaa;
            
        }

        div {
            max-width: 500px;
            margin: 10px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            
        }

        form {
            display: flex;
            flex-direction: column;
            
        }

        .field_data label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .field_data textarea {
            width: 100%;
            border-radius: 5px;
            resize: none;
        }

        input[type="file"] {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
        }

        button.btn {
            background-color: #7851a9;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.btn:hover {
            background-color: #6c49a0;
        }
        div img {
            display: block;
            margin: 0 auto; 
            max-width: 100%;
            height: auto; 
        }

    </style>
</head>
<body>
    <div>
        <h1>Edit Post</h1>
        <form action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <div>
                <label for="description">Text:</label>
                <textarea id="description" name="description" required><?php echo isset($post['description']) ? htmlspecialchars($post['description']) : ''; ?></textarea>
            </div>
            <div>
                <label for="file_path">Image:</label>
                <input type="file" id="file_path" name="file_path">
                <?php if (!empty($post['file_path'])): ?>
                    <p>Current Image:</p>
                    <img src="<?php echo htmlspecialchars($post['file_path']); ?>" alt="Current Image" style="width: 350px;" >
                <?php endif; ?>
            </div>
            <button type="submit" class="btn">Update</button>
        </form>
    </div>
</body>
</html>
