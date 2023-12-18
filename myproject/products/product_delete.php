<?php
require '../Database/db_connect.php';

// Check if the user is logged in
if (!empty($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
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
        echo "You do not have permission to delete this product.";
        exit();
    }

    // Check if the form is submitted
    if (isset($_POST['confirm_delete'])) {
        // Delete the product from the database
        $deleteSql = "DELETE FROM products WHERE product_id = $product_id";
        if (mysqli_query($conn, $deleteSql)) {
            header("Location: display_products.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    echo "Product ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE,edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/product_delete.css">
    <title>Delete Product</title>
</head>
<body>
<?php include '../navbar/navbar.php'; ?>
    <div class="container">
        <h1>Delete Product</h1>
        <div class="confirm">
            <p>Are you sure you want to delete this product?</p>
            <form method="post" action="product_delete.php?product_id=<?php echo $product_id; ?>">
                <button type="submit" name="confirm_delete">Confirm Delete</button>
                <a href="display_products.php">No</a>
            </form>
        </div>
    </div>
</body>
</html>
