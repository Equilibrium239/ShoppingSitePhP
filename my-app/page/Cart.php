<?php
session_start();
require_once(__DIR__ . '/../Models/Database.php');
require_once(__DIR__ . '/../Models/CartLogic.php');
require_once(__DIR__ . '/../Models/CartItem.php');

$database = new Database();
$cart = new Cart($database, session_id());

if (isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $fromPage = urlencode($_SERVER['PHP_SELF']);

    if ($_GET['action'] === 'add') {
        $cart->addItem($id, 1);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    if ($_GET['action'] === 'remove') {
        $cart->removeItem($id, 1);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$cartItems = $cart->getItems();
$totalPrice = $cart->getTotalPrice();
$itemsCount = $cart->getItemsCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
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
    <h2>Your Shopping Cart (<?php echo $itemsCount; ?> items)</h2>

    <?php if (empty($cartItems)): ?>
        <div class="empty-msg">
            <p>It's empty here! 🛒</p>
        </div>
    <?php else: ?>
        <?php foreach ($cartItems as $item): ?>
            <div class="cart-item">
                <img src="/my-app/image/<?php echo htmlspecialchars($item->imageUrl ?? ''); ?>" alt="">
                <div class="cart-item-info">
                    <strong><?php echo htmlspecialchars($item->productName); ?></strong><br>
                    <small>Antal: <?php echo $item->quantity; ?></small>
                </div>
                <div style="text-align: right;">
                    <div><?php echo $item->rowPrice; ?> USD</div>
                    <a href="?action=add&id=<?php echo $item->productId; ?>" style="color: #4CAF50; text-decoration: none; font-size: 0.8rem;">+</a>
                    &nbsp;
                    <a href="?action=remove&id=<?php echo $item->productId; ?>" class="remove-link">−</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="total">Total: <?php echo $totalPrice; ?> USD</div>

       <a href="Checkout.php" style="display: block; text-align: center; padding: 15px; background: #222; color: white; border-radius: 8px; margin-top: 20px; font-weight: 600; text-decoration: none;">
            Go to Checkout
        </a>

        <a href="Cloths.php" class="back-link">Continue Shopping</a>
    <?php endif; ?>
</div>

</body>
</html>