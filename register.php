<?php
// Include necessary files
include 'db_connect.php';
include 'functions.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_BCRYPT); // Hash password for security

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $error_message = "Email is already registered.";
    } else {
        // Insert user into the database
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password_hash')";
        if ($conn->query($sql) === TRUE) {
            $success_message = "Registration successful. You can now log in.";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Shop - Register</title>
    <link rel="stylesheet" href="style.css">
    <style>

/* Main Section */
main {
    background-color: #f9f9f9; /* Light gray background */
    padding: 2rem 1rem;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 120px); /* Adjust for header and footer */
}

main .container {
    background-color: #fff; /* White background for the form */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    max-width: 400px;
    width: 100%;
    padding: 2rem;
    text-align: center;
}

main h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #333; /* Dark text for better readability */
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

main .success {
    background-color: #ddffdd;
    color: #4f8a10;
    border: 1px solid #4f8a10;
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
    border-color: #6c63ff; /* Focus color matches the theme */
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
    background-color: #5348e0; /* Slightly darker on hover */
    transform: translateY(-2px); /* Lift effect */
}

main form button:active {
    transform: translateY(0); /* Reset on click */
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
    color: #5348e0; /* Darker hover effect */
}



    </style>
</head>
<body>
    <!-- Main Content -->
    <main>
        <div class="container">
            <h1>Create an Account</h1>
            
            <?php if (isset($error_message)) : ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($success_message)) : ?>
                <div class="success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>

            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </main>

    </body>
</html>
<?php include 'footer.php'; ?>