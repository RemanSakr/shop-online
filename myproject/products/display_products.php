<?php
require '../Database/db_connect.php'; // Include your database connection code

// Check if the user is logged in
if (!empty($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    // Fetch the user's products from the database
    $sql = "SELECT * FROM products WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    // Check if any products were found
    if (mysqli_num_rows($result) > 0) {
        $products = array();

        // Fetch each product as an associative array
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    } else {
        $no_products_message = "You have not added any products yet.";
    }
} else {
    header("Location: ../Auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/display_products.css">
    <title>Display Products</title>
</head>
<body>
<?php include '../navbar/navbar.php'; ?>

    <div class="container">
        <?php
        if (isset($no_products_message)) {
            echo '<p>' . $no_products_message . '</p>';
        } else {
            // Iterate through the user's products and display them in cards
            foreach ($products as $product) {
        ?>
<div class="card">
    <div class="card-image">
        <img src="../uploads/<?php echo $product['product_img']; ?>" alt="<?php echo $product['product_name']; ?>">
    </div>
    <div class="card-details">
        <h2><?php echo $product['product_name']; ?></h2>
        <p class="description"><?php echo $product['product_description']; ?></p>
        <p class="price">Price: $<?php echo $product['product_price']; ?></p>
        <div class="card-buttons">
            <a class="edit" href="product_edit.php?product_id=<?php echo $product['product_id']; ?>">Edit</a>
            <a class="delete" href="product_delete.php?product_id=<?php echo $product['product_id']; ?>">Delete</a>
        </div>
    </div>
</div>



        <?php
            }
        }
        ?>
    </div>
</body>
</html>
