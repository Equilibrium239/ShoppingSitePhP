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
    <?php 
    $my_products = [
        ['name' => 'Levis', 'size' => 'S', 'description' => 'stylish jean from Levis', 'imageUrl' => 'Levis Jeans.jpg', 'price' => '100.00'],
        ['name' => 'Napapijri', 'size' => 'XL', 'description' => 'stylish jacket from Napapijri', 'imageUrl' => 'Napapijri Jacket.jpg', 'price' => '300.00'],
        ['name' => 'Obey', 'size' => 'M', 'description' => 'Simple stylish look for an everyday use from obey', 'imageUrl' => 'Obey Hoodie.jpg', 'price' => '80.00'],
        ['name' => 'Nike Air Force', 'size' => '43', 'description' => 'An everyday shoe from nike', 'imageUrl' => 'Nike Air Force.jpg', 'price' => '150.00'],
        ['name' => 'Supreme', 'size' => 'L', 'description' => 'An simepl looking t-shirt from Supreme for an ev...', 'imageUrl' => 'Supreme T-shirt.jpg', 'price' => '120.00'],
        ['name' => 'Replay', 'size' => 'L', 'description' => 'Looking for an italien brand and not brake the b...', 'imageUrl' => 'Replay Jean.jpg', 'price' => '200.00']
    ];

      foreach ($my_products as $product): ?>
        <div class="product-card">
            <div class="product-image">
                <img src="images/<?php echo $product['imageUrl']; ?>" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="product-info">
                <h3><?php echo $product['name']; ?></h3>
                <p class="description" style="font-size: 0.8rem; color: #666;"><?php echo $product['description']; ?></p>
                <p class="size">Storlek: <?php echo $product['size']; ?></p>
                <p class="price"><?php echo $product['price']; ?> USD</p>
                <button class="BtnCloths">Lägg i varukorg</button>
            </div>
        </div>
    <?php endforeach; ?>
 </div>

    </main>

</body>
</html>