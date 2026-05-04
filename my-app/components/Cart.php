<?php
session_start(); 
require_once(__DIR__ . '/../Models/Database.php');
$db = new Database();


if (isset($_GET['action']) && $_GET['action'] == "add") {
    $id = intval($_GET['id']); 
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }


    $_SESSION['cart'][] = $id;
    
 
    header("Location: cart.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == "remove") {
    $key = $_GET['key'];
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
    }
    header("Location: cart.php");
    exit();
}


$cart_items = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $id) {
        $product = $db->getProduct($id); 
        if ($product) {
            
            $product['cart_key'] = $key;
            $cart_items[] = $product;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Kundvagn</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; padding: 40px; }
        .cart-wrapper { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .cart-item { display: flex; align-items: center; border-bottom: 1px solid #eee; padding: 15px 0; }
        .cart-item img { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; margin-right: 15px; }
        .cart-item-info { flex-grow: 1; }
        .remove-link { color: #ff4d4d; text-decoration: none; font-size: 0.8rem; }
        .total { font-weight: bold; font-size: 1.2rem; text-align: right; margin-top: 20px; }
        .empty-msg { text-align: center; color: #888; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #333; }
    </style>
</head>
<body>

<div class="cart-wrapper">
    <h2>Din varukorg</h2>

    <?php if (empty($cart_items)): ?>
        <div class="empty-msg">
            <p>Här var det tomt! 🛒</p>
            <a href="Cloths.php">Tillbaka till sortimentet</a>
        </div>
    <?php else: ?>
        <?php 
        $totalPrice = 0;
        foreach ($cart_items as $item): 
            $totalPrice += $item['price'];
        ?>
            <div class="cart-item">
                <img src="/my-app/image/<?php echo $item['imageUrl']; ?>" alt="">
                <div class="cart-item-info">
                    <strong><?php echo $item['name']; ?></strong><br>
                    <small>Storlek: <?php echo $item['size']; ?></small>
                </div>
                <div style="text-align: right;">
                    <div><?php echo $item['price']; ?> USD</div>
                    <a href="cart.php?action=remove&key=<?php echo $item['cart_key']; ?>" class="remove-link">Ta bort</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="total">Totalt: <?php echo $totalPrice; ?> USD</div>
        
        <button style="width: 100%; padding: 15px; background: #222; color: white; border: none; border-radius: 8px; margin-top: 20px; cursor: pointer;">
            Gå till kassan
        </button>
        
        <a href="Cloths.php" class="back-link">Fortsätt handla</a>
    <?php endif; ?>
</div>

</body>
</html>