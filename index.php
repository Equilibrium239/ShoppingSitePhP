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
    <header class="main-header">
        <nav class="navbar">
            <ul class="nav-menu">
                <li><a href="component/Cloths.php" class="nav-link">Cloths</a></li>
                <li>
                    <a href="cart.php" class="cart-wrapper">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="cart counter">0</span>
                    </a>
                </li>
                <li>
                    <a href="my-app/components/Medlemskap.php">
                        <button class="Btn">Medlem</button>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="hero">
        <div class="hero-content">
            <h1>Uppgradera din garderob</h1>
            <p>Utforska våra senaste kollektioner</p>
            <a href="my-app/components/Cloths.php" class="Btn">Handla nu</a>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-info">
                <h3>Om Oss</h3>
                <p>Vi är din destination för moderiktiga kläder. Som medlem får du alltid fri frakt och exklusiva erbjudanden</p>
            </div>
            <div class="footer-contact">
                <h3>Kontakta Oss</h3>
                <p>Email: info@ourstore.com</p>
                <p>Telefon: +46 123 456 789</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Our Store. All rights reserved.</p>
        </div>
    </footer>

    
</body>
</html>