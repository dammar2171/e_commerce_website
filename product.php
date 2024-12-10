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
 <style>
    .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.product-details {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.product-details h1 {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

.product-info {
    display: flex;
    flex-wrap: wrap; /* Adjusts layout for smaller screens */
    gap: 20px;
    align-items: flex-start;
}

.product-info img {
    width: 100%;
    max-width: 400px;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    object-fit: cover;
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition for hover effects */
}

.product-info img:hover {
    transform: scale(1.05); /* Slightly enlarge the image */
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2); /* Enhance shadow on hover */
    cursor: pointer; /* Change cursor to indicate interactivity */
}


.product-description {
    flex: 1; /* Takes up remaining space */
    padding: 10px;
}

.product-description p {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #555;
    margin-bottom: 10px;
}

.product-description strong {
    font-weight: bold;
    color: #333;
}

form {
    margin-top: 20px;
}

form input[type="number"] {
    width: 60px;
    padding: 5px;
    font-size: 1rem;
    margin-right: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

form .btn {
    padding: 10px 20px;
    font-size: 1rem;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form .btn:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-info {
        flex-direction: column; /* Stack elements vertically */
    }

    .product-info img {
        max-width: 100%; /* Allow image to take full width */
    }
}

 </style>
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
                    <img src="uploads/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
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
