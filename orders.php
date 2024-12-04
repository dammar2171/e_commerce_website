<?php
// Include necessary files
include 'session.php';
include 'db_connect.php';

// Fetch all orders from the database
$sql_orders = "SELECT * FROM orders ORDER BY order_date DESC";
$result_orders = $conn->query($sql_orders);

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $sql_update_status = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update_status);
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        $message = "Order status updated successfully!";
    } else {
        $message = "Error updating order status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

/* Sidebar styling */
.sidebar {
    width: 250px;
    background-color: #333;
    color: white;
    padding-top: 30px;
    position: fixed;
    height: 100%;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 15px;
    margin-bottom: 10px;
    font-size: 1.1rem;
    border-radius: 5px;
    transition: background 0.3s;
}

.sidebar a:hover {
    background-color: #6c63ff;
}

.sidebar .active {
    background-color: #6c63ff;
}

/* Main content area */
.main-content {
    margin-left: 250px;
    padding: 20px;
}

/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #6c63ff;
    color: white;
    font-size: 1.1rem;
}

tr:hover {
    background-color: #f2f2f2;
}

td {
    font-size: 0.9rem;
    color: #555;
}

td form {
    display: inline;
}

td select {
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-right: 10px;
}

td button {
    padding: 5px 10px;
    background-color: #6c63ff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
}

td button:hover {
    background-color: #5a54d0;
}

/* Message Styling */
p {
    text-align: center;
    font-size: 1.1rem;
    color: green;
}

/* Responsive Table */
@media screen and (max-width: 768px) {
    table {
        width: 100%;
        font-size: 0.8rem;
    }

    th, td {
        padding: 10px;
    }

    .sidebar {
        width: 200px;
    }

    .main-content {
        margin-left: 200px;
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
        <a href="orders.php" class="active">Manage Orders</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Manage Orders</h2>

        <!-- Display messages -->
        <?php if (isset($message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <!-- Orders Table -->
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left;">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_orders->num_rows > 0): ?>
                    <?php while ($order = $result_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['name']); ?></td>
                            <td><?php echo htmlspecialchars($order['email']); ?></td>
                            <td><?php echo htmlspecialchars($order['phone']); ?></td>
                            <td><?php echo htmlspecialchars($order['address']); ?></td>
                            <td><?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><?php echo date("d M Y, h:i A", strtotime($order['order_date'])); ?></td>
                            <td>
                                <!-- Update Status -->
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" required>
                                        <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
