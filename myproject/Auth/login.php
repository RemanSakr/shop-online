<?php
require '../Database/db_connect.php';

if(!empty($_SESSION["user_id"])){
    header("Location: ../products/display_products.php");
}

if(isset($_POST["submit"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if(mysqli_num_rows($result) > 0){
          if($password == $row["password"]){
              $_SESSION["login"] = true;
              $_SESSION["user_id"] = $row["user_id"];
              header("Location: ../products/display_products.php");
          }
          else{
            $registrationMessage = "Wrong Password";
          }
    }
    else{
        $registrationMessage = "User Not Exist";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/login.css"/>
    <title>Shop Online</title>
</head>
<body>
    <div class="main">
        <form action="" method="post">
            <h2>Website Shopping</h2>
            <img src="../media/logo.png" alt="logo">
            <h2>Login</h2>

            <input type="text" name="username" id="username" required placeholder="Username">
            <br>
            <input type="password" name="password" id="password" required placeholder="Password">
            <br><br>

            <?php
                if (!empty($registrationMessage)) {

                   $messageHtml = '<div>' . $registrationMessage . '</div>';
                   echo $messageHtml;
                }
            ?>

            <br>
            <button class="register" type="submit" name="submit">Login</button>
            <a class="login" href="register.php">Register</a>
        </form>
        
    </div>
</body>
</html>
