<?php 
$pageTitle = "Welcome to our store!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store</title>
    <link rel="stylesheet" href="my-app/src/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php require_once(__DIR__ . '/my-app/components/header.php'); ?>

    <main class="hero">
        <div class="hero-content">
            <h1>Uppgradera din garderob</h1>
            <p>Utforska våra senaste kollektioner</p>
            <a href="my-app/page/Cloths.php" class="Btn">Handla nu</a>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-info">
                <h3>About Us</h3>
                <p>We are your destination for fashionable clothing.</p>
            </div>
            <div class="footer-contact">
                <h3>Contact us</h3>
                <p>Email: info@ourstore.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Our Store. All rights reserved.</p>
        </div>
    </footer>
    
</body>
</html>