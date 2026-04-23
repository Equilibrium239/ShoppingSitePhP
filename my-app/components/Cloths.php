<?php 
    require_once(__DIR__ . '/../Models/Database.php');

    $db = new Database();

    $my_products = $db->getAllProducts();

    $popular_products = $db->getPopularProducts();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloths</title>
    <link rel="stylesheet" href="my-app/src/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>



<style>
     .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .product-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        border: 1px solid white;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        width: 100%;
        height: 300px;
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .product-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .product-info {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .product-info h3 {
      margin: 0 0 10px 0;
      font-size: 1.25rem;
      color: #222;
      text-transform: uppercase;
    }

    .description {
       font-size: 0.9rem;
       color: #666;
       margin-bottom: 15px;
       line-height: 1.4;
       height: 2.8em;
       overflow: hidden;
    }

    .size {
        font-size: 0.85rem;
        font-weight: bold;
        color: #888;
        margin-bottom: 10px;
        background: #f0f0f0;
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        align-self: flex-start;
    }

    .price {
        font-size: 1.4rem;
        font-weight: 800;
        color: #007bff;
        margin: 10px 0 20px 0;
    }

    .BtnCloths {
        width: 100%;
        padding: 12px;
        background-color: #222;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-top: auto;
    }

    .BtnCloths:hover {
        background-color: #007bff;
    }



</style>

<body>
    <header class="main-header">
        <nav class="navbar">
            <ul class="nav-menu">
                <li>
                    <a href="cart.php" class="cart-wrapper">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="cart counter">0</span>
                    </a>
                </li>
                <li>
                    <a href="Medlemskap.php">
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
      foreach ($my_products as $product): ?>
        <div class="product-card">
            <div class="product-image">
                <img src="/my-app/image/<?php echo $product['imageUrl']; ?>" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="product-info">
                <span class="category-badge" style="font-size: 0.7rem; text-transform: uppercase; color: #888;">
                <?php echo $product['category_name'] ?? 'Okategoriserad'; ?>


                <h3><a href="Product.php?id=<?php echo $product['id']; ?>">
                    <?php echo $product['name']; ?>
                </h3></a>
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