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
                        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
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
