<?php 
$pageTitle = "Welcome to our store!";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store</title>
    <link rel="stylesheet" href="src/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        color: #333;
    }


    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 5%;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.005);
    }


    .nav-link {
        text-decoration: none;
        color: #444;
        font-weight: 500;
        transition: color 0.3s;
    }

    .nav-link:hover { color: #007bff;}

    .cart-wrapper {
        text-decoration: none;
        color: #333;
        position: relative;
        font-size: 1.2rem;
    }

    .cart-counter {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #ff4757;
        color: white;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 50%;
    }

    .Btn {
        background-color: #222;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.3s ease;
    }


    .Btn:hover {
        background-color: #007bff;
        transform: translateY(-2px);
    }

    .hero {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: lightgrey;
        text-align: center;
    }

    .shop-btn {
        display: inline-block;
        margin-top: 1rem;
        padding: 12px 30px;
        background: #fff;
        color: #222;
        text-decoration: none;
        font-weight: bold;
        border-radius: 4px;
    }

    .site-footer {
        background: black;
        color: white;
        padding: 3rem 5% 1rem;
    }

    .footer-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .footer-bottom {
        border-top: 1px solid black;
        padding-top: 1rem;
        text-align: center;
        font-size: 0.9rem;
        color: gray;
    }


</style>

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
            <a href="component/Cloths.php" class="Btn">Handla nu</a>
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