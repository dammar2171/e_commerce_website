<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Website</title>
    <!-- Link to CSS -->
    <link rel="stylesheet" href="style.css">
    <style>
/* General reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

header {
    background-color: #6c63ff; /* Purple background */
    padding: 1rem 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.navbar .container-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.logo {
    font-size: 1.8rem;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 1.5rem;
}

.nav-links li a {
    text-decoration: none;
    font-size: 1rem;
    color: #fff;
    padding: 0.5rem 1rem;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-links li a:hover {
    background-color: #5348e0; /* Darker shade of purple */
    color: #fff;
    border-radius: 5px;
}

/* Responsive design for smaller screens */
@media (max-width: 768px) {
    .nav-links {
        flex-direction: column;
        display: none; /* Hidden by default */
        background-color: #6c63ff;
        position: absolute;
        top: 100%;
        right: 0;
        width: 100%;
        padding: 1rem 0;
    }

    .nav-links.active {
        display: flex;
    }

    .navbar .menu-toggle {
        display: block;
        cursor: pointer;
        font-size: 1.5rem;
        color: #fff;
    }

    .navbar .container {
        flex-wrap: wrap;
    }
}

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <div class="container-nav">
                <a href="index.php" class="logo">E-Shop</a>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="profile.php">Profile</a></li>
                </ul>
            </div>
        </nav>
    </header>
</body>
</html>