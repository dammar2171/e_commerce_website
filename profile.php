<?php
// Include necessary files
include 'session.php';
include 'db_connect.php';
include 'functions.php';

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle profile update
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    // Handle profile picture upload
    $profilePicture = $user['profile_picture']; // Default to the existing picture
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "uploads/";
        $profilePicture = $targetDir . basename($_FILES["profile_picture"]["name"]);
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // Create the uploads directory if it doesn't exist
        }
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profilePicture);
    }

    // Update query
    $updateSql = "UPDATE users SET name = ?, email = ?, password = ?, profile_picture = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssi", $name, $email, $password, $profilePicture, $userId);

    if ($updateStmt->execute()) {
        $message = "Profile updated successfully!";
        // Refresh user data
        $user['name'] = $name;
        $user['email'] = $email;
        $user['profile_picture'] = $profilePicture;
    } else {
        $message = "Failed to update profile.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!-- Include header -->
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .profile-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #34495e;
        }

        .profile-form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .profile-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .profile-form .btn {
            width: 100%;
            padding: 10px;
            background: #2c3e50;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .profile-form .btn:hover {
            background: #1b2838;
        }

        .logout-btn {
            display: block;
            text-align: center;
            margin: 20px auto;
            padding: 10px;
            background: #e74c3c;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            width: 200px;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .profile-picture {
            display: block;
            margin: 10px auto 20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #34495e;
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<main>
    <div class="container profile-container">
        <h1>Your Profile</h1>

        <!-- Display profile picture -->
        <?php if ($user['profile_picture']): ?>
            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="profile-picture">
        <?php else: ?>
            <img src="default-avatar.png" alt="Default Profile Picture" class="profile-picture">
        <?php endif; ?>

        <!-- Display Success/Error Message -->
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="profile.php" method="POST" class="profile-form" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $user['name']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>

            <label for="password">Password: <small>(Leave blank to keep current password)</small></label>
            <input type="password" name="password" id="password" placeholder="New Password">

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture">

            <button type="submit" class="btn">Update Profile</button>
        </form>

        <a href="profile.php?logout=true" class="logout-btn">Logout</a>
    </div>
</main>
</body>
</html>

<!-- Include footer -->
<?php include 'footer.php'; ?>
