<?php
// Include necessary files
include 'db_connect.php';
include 'functions.php';

// Fetch products from the database
$products = getProducts($conn);
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
    <style>
        .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Three cards per row */
    gap: 20px; /* Space between cards */
}

.product-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px); /* Subtle lift effect on hover */
}

.product-card img {
    width: 100%;
    height: 200px; /* Adjust height */
    object-fit: cover; /* Keeps the image aspect ratio intact */
    border-bottom: 1px solid #ddd; /* Separator between image and content */
}

.product-card h2 {
    font-size: 1.5rem;
    margin: 10px 15px;
    color: #333;
}

.product-card p {
    font-size: 1rem;
    margin: 10px 15px;
    color: #666;
}

.product-card .btn {
    display: inline-block;
    margin: 15px;
    padding: 10px 20px;
    font-size: 1rem;
    text-align: center;
    color: #fff;
    background-color: #007bff;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.product-card .btn:hover {
    background-color: #0056b3;
}

    </style>

</head>
<body>
<main>
    <div class="container">
        <h1>Welcome to E-Shop</h1>
        <p>Explore our collection of products and shop your favorites!</p>

        <div class="product-grid">
            <?php if (!empty($products)) : ?>
                <?php foreach ($products as $product) : ?>
                    <div class="product-card">
                        <img src="uploads/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
                        <h2><?php echo $product['name']; ?></h2>
                        <p>Price: $<?php echo $product['price']; ?></p>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No products available at the moment. Please check back later!</p>
            <?php endif; ?>
        </div>
    </div>
</main>
</body>
</html>



<!-- Include the footer -->
<?php include 'footer.php'; ?>
