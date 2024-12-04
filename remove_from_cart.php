<?php
// Include necessary files
include 'db_connect.php';
include 'functions.php';

// Start the session to manage cart data
session_start();

// Check if product ID is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = (int)$_GET['id'];

    // If the product exists in the cart, remove it
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]); // Remove the product from the cart
    }
}

// Redirect back to the cart page
header("Location: cart.php");
exit;
