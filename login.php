<?php
// Include necessary files
include 'db_connect.php';
include 'functions.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header('Location: profile.php'); // Redirect to profile or homepage
            exit;
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No account found with this email.";
    }
}
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Shop - Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
       
        /* Main Section */
        main {
            background-color: #f9f9f9;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 120px);
        }

        main .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            text-align: center;
        }

        main h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #333;
        }

        main .error, main .success {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        main .error {
            background-color: #ffdddd;
            color: #d8000c;
            border: 1px solid #d8000c;
        }

        main form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        main form input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        main form input:focus {
            border-color: #6c63ff;
            outline: none;
            box-shadow: 0 0 5px rgba(108, 99, 255, 0.5);
        }

        main form button {
            background-color: #6c63ff;
            color: #fff;
            font-size: 1rem;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        main form button:hover {
            background-color: #5348e0;
            transform: translateY(-2px);
        }

        main form button:active {
            transform: translateY(0);
        }

        main p {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #555;
        }

        main p a {
            color: #6c63ff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        main p a:hover {
            color: #5348e0;
        }

        
    </style>
</head>
<body>
  

    <!-- Main Content -->
    <main>
        <div class="container">
            <h1>Login to Your Account</h1>
            
            <?php if (isset($error_message)) : ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>

            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </main>

    
</body>
</html>
<?php include 'footer.php'; ?>