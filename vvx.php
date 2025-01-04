post 
id , primary key,
user_id int,
content text
image varchar



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
                        echo '<div class="message"> <p>register sucessfully</p>';
                        $_SESSION['new_register']=0;
                    }

                    ?>
                <?php if (isset($message)) { ?>
                <div class="message">

                    <p><?php echo $message; ?></p>
                </div>
                <?php } ?>
                <div>
                    <form action="" method="post">
                    <div class="field_data">
                        <label for="content">Your Text</label>
                        <textarea name="content" id="content" rows="10" required><?php echo htmlspecialchars($content); ?></textarea>
                    </div>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="save" value="Save">
                </div>
            </form>
            </div>
          </div>
       </div>

    </main>
</body>
</html>

