<?php
// Start a session for user authentication
// Ensure session is active only if necessary
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Fetch all products from the database.
 *
 * @param mysqli $conn Database connection
 * @return array List of products
 */
function getProducts($conn) {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

/**
 * Add a product to the cart.
 *
 * @param int $productId Product ID
 * @param int $quantity Quantity of the product
 */
function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

/**
 * Get the current cart items.
 *
 * @return array Cart items
 */
function getCartItems() {
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

/**
 * Calculate the total price of items in the cart.
 *
 * @param mysqli $conn Database connection
 * @return float Total price
 */
function calculateTotalPrice($conn) {
    $cartItems = getCartItems();
    $total = 0.0;

    foreach ($cartItems as $productId => $quantity) {
        $sql = "SELECT price FROM products WHERE id = $productId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $total += $product['price'] * $quantity;
        }
    }

    return $total;
}

/**
 * Clear the cart.
 */
function clearCart() {
    unset($_SESSION['cart']);
}
?>
