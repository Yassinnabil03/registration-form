<?php 
   session_start();

   include("php/confg.php");
   if(!isset($_SESSION['valid'])){
    header("Location: login.php");
   }

   if (isset($_POST['save'])) {
    $email = $_SESSION['valid'];
    $content = mysqli_real_escape_string($con, $_POST['content']);

    $query = "UPDATE users SET user_content='$content' WHERE Email='$email'";
    mysqli_query($con, $query) or die("Update error");
    $message = "saved!";
}

$email = $_SESSION['valid'];
$result = mysqli_query($con, "SELECT user_content FROM users WHERE Email='$email'") or die("Select error");
$row = mysqli_fetch_assoc($result);
$content = $row['user_content'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Home</title>
</head>
<body>

        <div class="right-links">
            <?php 
            
            $id = $_SESSION['id'];
            $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id");

            while($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_Age = $result['Age'];
                $res_Id = $result['Id'];
            }
            
            ?>
            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>

        </div>
    </div>
    <main>

       <div class="main-box top">
          <div class="top">
            <div class="box">
                <p>Hello <b><?php echo $res_Uname ?></b>, Welcome</p>
            </div>
            <div class="box">
                <p>Your email is <b><?php echo $res_Email ?></b>.</p>
            </div>
          </div>
          <div class="bottom">
            <div class="box">

                <p>And you are <b><?php echo $res_Age ?> years old</b>.</p> 
                <?php 
                    if (isset($_SESSION['new_register']) && $_SESSION['new_register']==1){
                        echo '<div class="message"> <p>register successfully</p>';
                        $_SESSION['new_register']=0;
                    }
                ?>

                <?php if (isset($message)) { ?>
                <div class="message">
                    <p><?php echo $message; ?></p>
                </div>
                <?php } ?>


                <form action="upload_area.php" method="GET">
                    <button type="submit" class="btn">Want to Upload</button>
                </form>

-
                <div>
                    <h3>Your Uploaded Files:</h3>
                    <?php 
                    $userId = $_SESSION['id'];
                    $query = "SELECT * FROM user_uploads WHERE user_id = '$userId' ORDER BY upload_date DESC";
                    $result = mysqli_query($con, $query) or die("Select error");

                    if (mysqli_num_rows($result) > 0) {
                        echo "<table border='1' style='width:50%; text-align: center; border-collapse: collapse;'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>image</th>";
                        echo "<th>Description</th>";
                        echo "<th>edit</th>";
                        echo "<th>delete</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td><img src='" . htmlspecialchars($row['file_path']) . "' alt='Post Image' style='width: 100px;'></td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td><a href='edit.php?id=" . $row['id'] . "' class='btn'>Edit</a></td>";
                            echo "<td><a href='delete.php?id=" . $row['id'] . "' class='btn'>Delete</a></td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<p>No files uploaded yet.</p>";
                    }
                    ?>

                </div>
                
            </div>
          </div>
       </div>

    </main>
</body>
</html>
