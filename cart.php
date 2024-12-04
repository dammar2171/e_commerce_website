<?php
// Include necessary files
include 'db_connect.php';
include 'functions.php';

// Start the session if not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize the cart if it's not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding a product to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Check if product is valid
    if ($productId > 0 && $quantity > 0) {
        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity; // Increase quantity if product already in cart
        } else {
            $_SESSION['cart'][$productId] = $quantity; // Add new product to cart
        }
    }
}

// Fetch products from the cart to display
$productIds = array_keys($_SESSION['cart']);
$products = [];

if (!empty($productIds)) {
    $ids = implode(',', $productIds);
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
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
    <style>
        /* General styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

/* Container styles */
.container {
    width: 90%;
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Cart heading */
h1 {
    font-size: 2.5em;
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

/* Cart table styles */
.cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

.cart-table th,
.cart-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.cart-table th {
    background-color: #f8f8f8;
    font-weight: bold;
}

.cart-table td {
    background-color: #fff;
}

/* Input and button styles */
input[type="number"] {
    padding: 8px;
    width: 60px;
    text-align: center;
    border-radius: 4px;
    border: 1px solid #ddd;
}

button[type="submit"], .btn {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button[type="submit"]:hover, .btn:hover {
    background-color: #218838;
}

a.btn {
    display: inline-block;
    text-align: center;
    padding: 12px 25px;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    text-decoration: none;
}

a.btn:hover {
    background-color: #0056b3;
}

/* Checkout section */
.checkout {
    text-align: center;
    margin-top: 20px;
}

.checkout a {
    font-size: 1.2em;
    padding: 12px 25px;
    background-color: #ff5722;
    color: white;
    border-radius: 4px;
    text-decoration: none;
}

.checkout a:hover {
    background-color: #e64a19;
}

/* Empty cart message */
p {
    font-size: 1.2em;
    text-align: center;
    color: #777;
    margin-top: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 95%;
        padding: 15px;
    }

    h1 {
        font-size: 2em;
    }

    .cart-table th,
    .cart-table td {
        padding: 12px;
    }

    .checkout a {
        padding: 10px 20px;
    }

    button[type="submit"] {
        padding: 8px 15px;
    }
}

    </style>

</head>
<body>
<main>
    <div class="container">
        <h1>Your Cart</h1>

        <?php if (!empty($products)) : ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?php echo $product['name']; ?></td>
                            <td>$<?php echo $product['price']; ?></td>
                            <td>
                                <form action="cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
                                    <input type="number" name="quantity" value="<?php echo $_SESSION['cart'][$product['id']]; ?>" min="1" />
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            </td>
                            <td>$<?php echo $product['price'] * $_SESSION['cart'][$product['id']]; ?></td>
                            <td>
                                <a href="remove_from_cart.php?id=<?php echo $product['id']; ?>" class="btn">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Checkout Button -->
            <div class="checkout">
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
            </div>

        <?php else : ?>
            <p>Your cart is empty. Start shopping now!</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
<!-- Include the footer -->
<?php include 'footer.php'; ?>
