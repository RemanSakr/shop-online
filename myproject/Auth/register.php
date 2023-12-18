<?php
require '../Database/db_connect.php'; // Include the database connection 

if (!empty($_SESSION["user_id"])) {
    header("Location: ../display_product.php");
}

// Initialize variables for messages
$registrationMessage = '';

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirmpassword"];

    // Handle the uploaded image
    if (isset($_FILES['user_img'])) {
        $file_name = $_FILES['user_img']['name'];
        $file_tmp = $_FILES['user_img']['tmp_name'];
        $file_type = $_FILES['user_img']['type'];
        $file_size = $_FILES['user_img']['size'];

        // Define the directory where you want to store the user images
        $upload_directory = '../uploads/';

        // Generate a unique filename to avoid overwriting existing files
        $unique_filename = uniqid() . '_' . $file_name;

        $upload_path = $upload_directory . $unique_filename;

        // You may want to add additional validation here, such as file type and size checks

        // Move the uploaded image to the desired location
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Image upload successful
        } else {
            $registrationMessage = "Image upload failed.";
        }
    }

    // Check for duplicate users
    $duplicate = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' OR email = '$email'");

    if (mysqli_num_rows($duplicate) > 0) {
        $registrationMessage = "This user already exists";
    } else {
        if ($password == $confirm_password) {
            // Insert the user into the database, including the image path
            $query = "INSERT INTO users (name, username, email, password, user_img) 
                      VALUES ('$name', '$username', '$email', '$password', '$upload_path')";

            if (mysqli_query($conn, $query)) {
                $registrationMessage = "Registration Successful";
            } else {
                $registrationMessage = "Registration failed. Please try again later";
            }
        } else {
            $registrationMessage = "Passwords do not match";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/register.css"/>
    <title>Shop Online</title>
</head>

<body>
    <div class="main">
        <form action="" method="post" enctype="multipart/form-data">
            <h2>Website Shopping</h2>
            <img src="../media/logo.png" alt="logo">
            <h2>Registration</h2>

            <input type="text" name="name" id="name" required placeholder="Name">
            <br>
            <input type="text" name="username" id="username" required placeholder="Username">
            <br>
            <input type="email" name="email" id="email" required placeholder="Email">
            <br>
            <input type="password" name="password" id="password" required placeholder="Password">
            <br>
            <input type="password" name="confirmpassword" id="confirmpassword" required placeholder="Confirm Password">
            <br>
            <input type="file" name="user_img"  placeholder="Upload Profile Image">
            <br>
            <?php
                if (!empty($registrationMessage)) {

                   $messageHtml = '<div>' . $registrationMessage . '</div>';
                   echo $messageHtml;
                }
            ?>

            <br>
            <button class="register" type="submit" name="submit">Register</button>
            <a class="login" href="login.php">Login</a>
        </form>
    </div>
</body>
</html>
