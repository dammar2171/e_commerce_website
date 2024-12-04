<?php
// Include necessary files
include 'session.php';
include 'db_connect.php';

// Directory to store uploaded images
$image_dir = "uploads/products/";

// Ensure the upload directory exists
if (!is_dir($image_dir)) {
    mkdir($image_dir, 0777, true);
}

// Fetch all products from the database
$sql_products = "SELECT * FROM products";
$result_products = $conn->query($sql_products);

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete the image file
    $sql_get_image = "SELECT image FROM products WHERE id = ?";
    $stmt_get_image = $conn->prepare($sql_get_image);
    $stmt_get_image->bind_param("i", $delete_id);
    $stmt_get_image->execute();
    $stmt_get_image->bind_result($image_path);
    $stmt_get_image->fetch();
    if (file_exists($image_dir . $image_path)) {
        unlink($image_dir . $image_path);
    }
    $stmt_get_image->close();

    // Delete the product from the database
    $sql_delete = "DELETE FROM products WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $delete_id);
    $stmt_delete->execute();
    header("Location: manage_products.php");
    exit();
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $product_name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = "";

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']);
        $image_path = $image_dir . time() . "_" . $image_name; // Unique file name
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $image = time() . "_" . $image_name; // Save only the file name
    }

    // Insert product into database
    $sql_add = "INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)";
    $stmt_add = $conn->prepare($sql_add);
    $stmt_add->bind_param("sdss", $product_name, $price, $description, $image);
    $stmt_add->execute();
    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your CSS styles here */
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #333;
    color: white;
    position: fixed;
    height: 100%;
    padding-top: 30px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 15px 20px;
    margin-bottom: 10px;
    font-size: 1.1rem;
    border-radius: 5px;
    transition: background 0.3s;
}

.sidebar a:hover {
    background-color: #6c63ff;
}

/* Main Content */
.main-content {
    margin-left: 270px;
    padding: 20px;
}

h1, h2 {
    color: #333;
    margin-bottom: 20px;
}

/* Add Product Form */
.add-product-form {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: auto;
    margin-bottom: 30px;
}

.add-product-form h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
input[type="number"],
input[type="file"],
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
    margin-top: 5px;
}

textarea {
    resize: none;
}

input[type="submit"] {
    background-color: #6c63ff;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    transition: background 0.3s;
}

input[type="submit"]:hover {
    background-color: #5a54d0;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #6c63ff;
    color: white;
    text-transform: uppercase;
    font-size: 0.9rem;
}

table td img {
    max-width: 80px;
    height: auto;
    border-radius: 5px;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.edit-btn,
.delete-btn {
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 0.9rem;
    transition: background 0.3s, color 0.3s;
}

.edit-btn {
    background-color: #6c63ff;
    color: white;
}

.edit-btn:hover {
    background-color: #5a54d0;
}

.delete-btn {
    background-color: #ff6b6b;
    color: white;
}

.delete-btn:hover {
    background-color: #d65353;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: static;
    }

    .main-content {
        margin-left: 0;
    }

    table {
        font-size: 0.9rem;
    }

    .action-buttons {
        flex-direction: column;
        gap: 5px;
    }
}

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="orders.php">Manage Orders</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Manage Products</h1>

        <!-- Add Product Form -->
        <div class="add-product-form">
            <h2>Add New Product</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" name="image" id="image" accept="image/*" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="add_product" value="Add Product">
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <h2>All Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_products->num_rows > 0): ?>
                    <?php while ($product = $result_products->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <?php if ($product['image']): ?>
                                    <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="width: 80px; height: auto;">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td class="action-buttons">
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="edit-btn">Edit</a>
                                <a href="manage_products.php?delete_id=<?php echo $product['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
