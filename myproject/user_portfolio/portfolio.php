<?php
require '../Database/db_connect.php'; // Include your database connection code

// Check if the user is logged in
if (!empty($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    // Fetch the user's information, including the image
    $sql = "SELECT name, username, email, user_img FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $name = $user['name'];
        $username = $user['username'];
        $email = $user['email'];
        $image = $user['user_img'];
    } else {
        echo "User not found.";
    }
} else {
    header("Location: ../Auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .container {
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            margin: 10% auto; 
        }

        .welcome{
            text-align: center;
        }

        .user-info {
            font-size: 18px;
            margin-top: 20px;
        }

        .user-info p {
            margin: 10px 0;
        }

        .user-image {
            max-width: 100%; /* Ensure the image fits within the container */
        }
    </style>
    <title>User Portfolio</title>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container">
        <h1 class="welcome">Welcome, <?php echo $name; ?>!</h1>
        <?php if (!empty($image)) : ?>
            <img class="user-image" src="../uploads/<?php echo $image; ?>" alt="User Image">
        <?php endif; ?>
        <div class="user-info">
            <p><strong>Name:</strong> <?php echo $name; ?></p>
            <p><strong>Username:</strong> <?php echo $username; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
        </div>
    </div>
</body>
</html>

