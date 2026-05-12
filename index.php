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
        <nav class="navbar" style="display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
            <div class="logo">
                <a href="index.php" style="text-decoration: none; color: inherit; font-weight: bold;">OUR STORE</a>
            </div>

            <div class="nav-search" style="flex-grow: 0.5; margin: 0 20px;">
                <form action="my-app/components/Cloths.php" method="GET" style="display: flex; width: 100%;">
                    <input type="text" name="search" placeholder="Sök produkter..." 
                           style="width: 100%; padding: 8px 12px; border-radius: 20px 0 0 20px; border: 1px solid #ccc; outline: none;">
                    <button type="submit" style="padding: 8px 15px; border-radius: 0 20px 20px 0; border: 1px solid #ccc; border-left: none; background: #222; color: white; cursor: pointer;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
            <ul class="nav-menu" style="display: flex; list-style: none; gap: 20px; align-items: center; margin: 0;">
                <li><a href="my-app/components/Cloths.php" class="nav-link">Cloths</a></li>
                <li>
                    <a href="my-app/components/Cart.php" class="cart-wrapper">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="cart counter">0</span>
                    </a>
                </li>
                <li>
                    <a href="my-app/components/Medlemskap.php">
                        <button class="Btn" style="margin: 0;">Medlem</button>
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
                <p>Vi är din destination för moderiktiga kläder.</p>
            </div>
            <div class="footer-contact">
                <h3>Kontakta Oss</h3>
                <p>Email: info@ourstore.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Our Store. All rights reserved.</p>
        </div>
    </footer>
    
</body>
</html>