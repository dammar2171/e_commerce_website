<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        footer {
    background-color: #333; /* Dark background */
    color: #fff; /* White text */
    padding: 1.5rem 0;
    text-align: center;
}

footer .container-foot {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

footer p {
    font-size: 1rem;
    margin-bottom: 1rem;
    font-family: Arial, sans-serif;
}

.footer-links {
    list-style: none;
    padding: 0;
    display: flex;
    justify-content: center;
    gap: 1.5rem; /* Space between links */
}

.footer-links li {
    display: inline;
}

.footer-links li a {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    font-family: Arial, sans-serif;
    transition: color 0.3s ease;
}

.footer-links li a:hover {
    color: #6c63ff; /* Highlight link on hover */
}

/* Responsive design for smaller screens */
@media (max-width: 768px) {
    .footer-links {
        flex-direction: column;
        gap: 1rem; /* Reduce spacing for smaller screens */
    }
}

    </style>
</head>
<body>
<footer>
        <div class="container-foot">
            <p>&copy; <?php echo date("Y"); ?> E-Shop. All Rights Reserved.</p>
            <ul class="footer-links">
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="terms.php">Terms & Conditions</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>