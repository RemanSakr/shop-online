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

// Check if a product ID is provided in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch the product information
    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    // Check if the product belongs to the logged-in user
    if ($product['user_id'] != $user_id) {
        echo "You do not have permission to edit this product.";
        exit();
    }
} else {
    echo "Product ID not provided.";
    exit();
}

if (isset($_POST['edit_product'])) {
    // Validate and process the form data
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    // Handle the uploaded image
    if (isset($_FILES['product_img']) && !empty($_FILES['product_img']['name'])) {
        $file_name = $_FILES['product_img']['name'];
        $file_tmp = $_FILES['product_img']['tmp_name'];
        $file_type = $_FILES['product_img']['type'];
        $file_size = $_FILES['product_img']['size']; 

        // Move the uploaded image to a desired location
        $upload_directory = '../uploads/'; // Define the directory where you want to store the images
        $upload_path = $upload_directory . $file_name;
        move_uploaded_file($file_tmp, $upload_path);

        // Update the product image path
        $sql = "UPDATE products SET product_name = '$product_name', product_description = '$product_description', product_price = '$product_price', product_img = '$upload_path' WHERE product_id = $product_id";
    } else {
        // No new image uploaded, update without changing the image
        $sql = "UPDATE products SET product_name = '$product_name', product_description = '$product_description', product_price = '$product_price' WHERE product_id = $product_id";
    }

    if (mysqli_query($conn, $sql)) {
        $success_message = "Product updated successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE,edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/product_edit.css">
    <title>Edit Product</title>
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container">
        <h1>Edit Product</h1>
        
        <?php if (isset($success_message)) : ?>
            <div class="message success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)) : ?>
            <div class="message error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post" action="product_edit.php?product_id=<?php echo $product_id; ?>" enctype="multipart/form-data">
            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" required value="<?php echo $product['product_name']; ?>">
            
            <label for="product_description">Product Description</label>
            <textarea name="product_description" required><?php echo $product['product_description']; ?></textarea>
       
            <label for="product_price">Product Price</label>
            <input type="number" name="product_price" required value="<?php echo $product['product_price']; ?>">
            
            <label for="product_img">Product Image</label>
            <img src="<?php echo $product['product_img']; ?>" alt="Current Image">
            <input type="file" name="product_img">
            
            <button type="submit" name="edit_product">Save Changes</button>
        </form>
        <a class="back" href="display_products.php">Back to My Products</a>
    </div>
</body>
</html>

