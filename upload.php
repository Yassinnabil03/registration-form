<?php
session_start();
include("php/confg.php");

if (isset($_POST['submit'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    if (!isset($con)) {
        die("Database connection error.");
    }

    $description = mysqli_real_escape_string($con, $_POST['content']);
    $userId = $_SESSION['id'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000000) { 
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileLocation = 'uploads/' . $fileNameNew;

                if (move_uploaded_file($fileTmpName, $fileLocation)) {
                    $query = "INSERT INTO user_uploads (`user_id`, `file_name`, `file_path`, `description`) VALUES ('$userId', '$fileName', '$fileLocation', '$description')";
                    mysqli_query($con, $query) or die("Database insert error");

                    header("Location: home.php");
                    exit();
                } else {
                    echo "Failed to move the uploaded file!";
                }
            } else {
                echo "Your file is too big!";
            }
        } else {
            echo "There was an error uploading your file!";
        }
    } else {
        echo "You cannot upload files of this type!";
    }
}
?>
