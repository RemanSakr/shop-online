<?php
require '../Database/db_connect.php';

// Check if the user is logged in
if (!empty($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
    $row = mysqli_fetch_assoc($result);
} else {
    header("Location: ../Auth/login.php");
    exit();
}

if (isset($_POST['add_product'])) {
    // Validate and process the form data
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    // Handle the uploaded image
    if (isset($_FILES['product_img'])) {
        $file_name = $_FILES['product_img']['name'];
        $file_tmp = $_FILES['product_img']['tmp_name'];
        $file_type = $_FILES['product_img']['type'];
        $file_size = $_FILES['product_img']['size'];

        // Move the uploaded image to a desired location
        $upload_directory = '../uploads/'; // Define the directory where you want to store the images
        $upload_path = $upload_directory . $file_name;
        move_uploaded_file($file_tmp, $upload_path);
    }

    // Get the user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Insert the product into the database
    $sql = "INSERT INTO products (product_name, product_description, product_price, product_img, user_id) VALUES ('$product_name', '$product_description', '$product_price', '$upload_path', '$user_id')"; // Include the uploaded file path in the VALUES list

    if (mysqli_query($conn, $sql)) {
        $success_message = "Product added successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/product_add.css"/>
    <title>Product Management</title>
    <style>
       
    </style>
</head>
<body>
<?php include '../navbar/navbar.php'; ?>

    <h1>Welcome <?php echo $row["name"] ?></h1>

    <div class="form-container">
        <?php if (isset($success_message)) : ?>
            <div class="message success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)) : ?>
            <div class="message error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post" action="product_add.php" enctype="multipart/form-data">
            <input type="text" name="product_name" placeholder="Product Name" required>

            <textarea name="product_description" rows="4" cols="50" placeholder="Product Description" required></textarea>

            <input type="number" name="product_price" placeholder="Product Price" required>

            <input type="file" name="product_img"  required>

            <button type="submit" name="add_product">Add Product</button>
        </form>
        <a class="view-product-btn" href="display_products.php">View Products</a>
    </div>
</body>
</html>

