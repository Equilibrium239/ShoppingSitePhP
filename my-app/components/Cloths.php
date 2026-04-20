<?php 
    $api_url = "http://localhost:3000/api/items";

    $response = file_get_contents($api_url);

    $products = json_decode($response, true);
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    <main class="container">
        <h1>Sortiment</h1>

       <div class="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo $product['title']; ?></h3>
                            <p class="price"><?php echo $product['price']; ?> USD</p>
                            <button class="Btn">Lägg i varukorg</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Kunde inte hämta några kläder just nu.</p>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>