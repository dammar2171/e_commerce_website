<?php
// Include necessary files
include 'db_connect.php';
include 'functions.php';

// this code adjust session according to it 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the order ID is passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $orderId = (int)$_GET['id'];

    // Fetch the order details
    $sql = "SELECT * FROM orders WHERE id = $orderId";
    $result = $conn->query($sql);
    $order = $result->fetch_assoc();

    // Fetch the items in the order
    $sql = "SELECT order_items.quantity, products.name, products.price 
            FROM order_items 
            JOIN products ON order_items.product_id = products.id 
            WHERE order_items.order_id = $orderId";
    $orderItems = $conn->query($sql);
} else {
    // Redirect to homepage if no valid order ID is provided
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

</head>
<body>
<main>
    <div class="container">
        <h1>Order Confirmation</h1>

        <h3>Thank you for your order!</h3>
        <p>Your order has been successfully placed. Below are your order details:</p>

        <h3>Order #<?php echo $order['id']; ?></h3>
        <p><strong>Name:</strong> <?php echo $order['name']; ?></p>
        <p><strong>Shipping Address:</strong> <?php echo $order['address']; ?></p>
        <p><strong>Email:</strong> <?php echo $order['email']; ?></p>
        <p><strong>Phone Number:</strong> <?php echo $order['phone']; ?></p>

        <h3>Order Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalAmount = 0;
                while ($item = $orderItems->fetch_assoc()) {
                    $totalAmount += $item['price'] * $item['quantity'];
                ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo $item['price']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Total Amount: $<?php echo $totalAmount; ?></h3>

        <p>If you have any questions, feel free to contact us at dammarbhatt111@gmail.com.</p>
        <a href="index.php" class="btn">Back to Shopping</a>
    </div>
</main>
</body>
</html>

<!-- Include the footer -->
<?php include 'footer.php'; ?>
