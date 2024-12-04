<?php
// Include necessary files
include 'db_connect.php';
include 'functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: index.php"); // Redirect to homepage if the cart is empty
    exit;
}

// Handle form submission (if the user is submitting the order)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the shipping details from the form
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Calculate the total amount for the order
    $totalAmount = 0;
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $sql = "SELECT price FROM products WHERE id = $productId";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
        $totalAmount += $product['price'] * $quantity;
    }

    // Insert the order details into the database (simplified version)
    $sql = "INSERT INTO orders (name, address, email, phone, total_amount) 
            VALUES ('$name', '$address', '$email', '$phone', $totalAmount)";
    
    if ($conn->query($sql)) {
        // Get the inserted order ID
        $orderId = $conn->insert_id;

        // Insert the order items into the order_items table
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity) 
                    VALUES ($orderId, $productId, $quantity)";
            $conn->query($sql);
        }

        // Clear the cart after successful order submission
        unset($_SESSION['cart']);

        // Redirect to a confirmation page or order summary page
        header("Location: order_confirmation.php?id=$orderId");
        exit;
    } else {
        $error = "Error placing the order. Please try again.";
    }
}
?>

<?php include 'header.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <style>
        /* General styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}


.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    margin-top: 50px;
}

h1 {
    text-align: center;
    color: #333;
    font-size: 2rem;
    margin-bottom: 30px;
}

h3 {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 15px;
}

label {
    display: block;
    font-size: 1rem;
    color: #333;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
textarea {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 1rem;
    margin-bottom: 15px;
    box-sizing: border-box;
}

textarea {
    height: 100px;
    resize: vertical;
}

button[type="submit"] {
    background-color: #6c63ff;
    color: white;
    border: none;
    padding: 12px 25px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 5px;
    width: 100%;
    transition: background-color 0.3s;
}

button[type="submit"]:hover {
    background-color: #5a54d0;
}

.error {
    color: red;
    font-size: 1rem;
    text-align: center;
    margin-top: 20px;
}

/* Order summary */
.order-summary {
    margin-top: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #6c63ff;
    color: white;
    font-size: 1rem;
}

table td {
    font-size: 1rem;
}

h3 {
    text-align: right;
    font-size: 1.25rem;
    color: #333;
}

/* Shipping Details */
.shipping-details {
    margin-bottom: 30px;
}

/* Make sure form elements align correctly */
.shipping-details label {
    margin-bottom: 5px;
}

.shipping-details input[type="text"],
.shipping-details input[type="email"],
.shipping-details textarea {
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    .shipping-details,
    .order-summary {
        margin-top: 15px;
    }

    table th, table td {
        font-size: 0.9rem;
        padding: 8px;
    }

    button[type="submit"] {
        padding: 10px 20px;
    }
}
 

    </style>

</head>
<body>
    <main>
    <div class="container">
        <h1>Checkout</h1>

        <form action="checkout.php" method="POST">
            <div class="shipping-details">
                <h3>Shipping Information</h3>
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" required />

                <label for="address">Shipping Address:</label>
                <textarea name="address" id="address" required></textarea>

                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" required />

                <label for="phone">Phone Number:</label>
                <input type="text" name="phone" id="phone" required />
            </div>

            <div class="order-summary">
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
                        foreach ($_SESSION['cart'] as $productId => $quantity) {
                            $sql = "SELECT * FROM products WHERE id = $productId";
                            $result = $conn->query($sql);
                            $product = $result->fetch_assoc();
                            $totalAmount += $product['price'] * $quantity;
                        ?>
                        <tr>
                            <td><?php echo $product['name']; ?></td>
                            <td>$<?php echo $product['price']; ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td>$<?php echo $product['price'] * $quantity; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <h3>Total: $<?php echo $totalAmount; ?></h3>
            </div>

            <button type="submit" class="btn">Place Order</button>
        </form>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>

<!-- Include the footer -->
<?php include 'footer.php'; ?>

