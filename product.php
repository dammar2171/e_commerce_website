<?php
// Include necessary files
include 'db_connect.php';
include 'functions.php';

// Get the product ID from the URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details from the database
if ($productId > 0) {
    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();
} else {
    // If no product ID is provided, redirect to homepage
    header("Location: index.php");
    exit;
}

?>

<!-- Include the header -->
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    
</body>
</html>
<main>
    <div class="container">
        <?php if ($product) : ?>
            <div class="product-details">
                <h1><?php echo $product['name']; ?></h1>
                <div class="product-info">
                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
                    <div class="product-description">
                        <p><?php echo $product['description']; ?></p>
                        <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>

                        <!-- Add to Cart Form -->
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
                            <input type="number" name="quantity" value="1" min="1" />
                            <button type="submit" class="btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <p>Product not found.</p>
        <?php endif; ?>
    </div>
</main>
<!-- Include the footer -->
<?php include 'footer.php'; ?>
