<?php session_start()?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>sign up</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
        <?php 
        include ("php/confg.php");
        if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $age = $_POST['age'];
            $password = $_POST['password'];

        $verify_query = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");
        if(mysqli_num_rows($verify_query) !=0){
            echo "<div class='message'>
                      <p>This email is used, Try another One Please!</p>
                  </div> <br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";

        }
        else{
            mysqli_query($con, "INSERT INTO users(`Username`, `Email`, `Age`, `Password`) Values('$username', '$email', '$age', '$password')") or die("error");
            $id=mysqli_insert_id($con);
            $_SESSION['valid'] = $email;
            $_SESSION['username'] = $username;
            $_SESSION['age'] = $age;
            $_SESSION['id'] = $id;
            $_SESSION['new_register'] = 1;
            header("Location: home.php");
            exit;
        }
        
        }else{
        
        ?>

       

            <header>registration</header>
            <form action="" method="post">

                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="age">age</label>
                    <input type="number" name="age" id="age"  required>
                </div>
                <div class="field input">
                    <label for="password">password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field ">
                    <input type="submit" class="btn" name="submit" value="login" required>
                </div>
                <div class="links">
                    Already a member <a href="login.php">sign in</a>
                    </a>
                </div>
            </form>    
        </div>
       <?php } ?>
    </div>
</body>
</html>