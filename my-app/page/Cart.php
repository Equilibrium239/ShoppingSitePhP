<?php
session_start();
require_once(__DIR__ . '/../Models/Database.php');
require_once(__DIR__ . '/../Models/CartLogic.php');
require_once(__DIR__ . '/../Models/CartItem.php');

$database = new Database();
$cart = new Cart($database, session_id());

if (isset($_GET['action'])) {
    $id = intval($_GET['id']);

    if ($_GET['action'] === 'add') {
        $cart->addItem($id, 1);

        if (isset($_GET['ajax'])) {
            $item = $cart->getCartItem($id);
            header('Content-Type: application/json');
            echo json_encode([
                'count'    => $cart->getItemsCount(),
                'total'    => $cart->getTotalPrice(),
                'rowPrice' => $item->rowPrice,
                'quantity' => $item->quantity
            ]);
            exit();
        }

        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : $_SERVER['PHP_SELF'];
        header("Location: " . $redirect);
        exit();
    }

    if ($_GET['action'] === 'remove') {
        $cart->removeItem($id, 1);

        if (isset($_GET['ajax'])) {
            $item = $cart->getCartItem($id);
            header('Content-Type: application/json');
            echo json_encode([
                'count'    => $cart->getItemsCount(),
                'total'    => $cart->getTotalPrice(),
                'rowPrice' => $item ? $item->rowPrice : 0,
                'quantity' => $item ? $item->quantity : 0,
                'removed'  => $item === null
            ]);
            exit();
        }

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
        .remove-link { color: #ff4d4d; text-decoration: none; font-size: 0.8rem; cursor: pointer; }
        .add-link { color: #4CAF50; text-decoration: none; font-size: 0.8rem; cursor: pointer; }
        .total { font-weight: bold; font-size: 1.2rem; text-align: right; margin-top: 20px; }
        .empty-msg { text-align: center; color: #888; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #333; }
    </style>
</head>
<body>

<div class="cart-wrapper">
    <h2>Your Shopping Cart (<span id="header-count"><?php echo $itemsCount; ?></span> items)</h2>

    <div id="empty-msg" class="empty-msg" style="display: <?php echo empty($cartItems) ? 'block' : 'none'; ?>;">
        <p>It's empty here! 🛒</p>
        <a href="Cloths.php" class="back-link">Back to Catalog</a>
    </div>

    <div id="cart-content" style="display: <?php echo empty($cartItems) ? 'none' : 'block'; ?>;">

        <div id="cart-items">
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item" id="item-<?php echo $item->productId; ?>">
                    <img src="/my-app/image/<?php echo htmlspecialchars($item->imageUrl ?? ''); ?>" alt="">
                    <div class="cart-item-info">
                        <strong><?php echo htmlspecialchars($item->productName); ?></strong><br>
                        <small>Antal: <span class="item-quantity"><?php echo $item->quantity; ?></span></small>
                    </div>
                    <div style="text-align: right;">
                        <div><span class="item-price"><?php echo $item->rowPrice; ?></span> USD</div>
                        <span class="add-link" onclick="cartAction(<?php echo $item->productId; ?>, 'add')">+</span>
                        &nbsp;
                        <span class="remove-link" onclick="cartAction(<?php echo $item->productId; ?>, 'remove')">−</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="total">Total: <span id="total-price"><?php echo $totalPrice; ?></span> USD</div>

        <a href="Checkout.php" style="display: block; text-align: center; padding: 15px; background: #222; color: white; border-radius: 8px; margin-top: 20px; font-weight: 600; text-decoration: none;">
            Go to Checkout
        </a>

        <a href="Cloths.php" class="back-link">Continue Shopping</a>

    </div>
</div>

<script>
function cartAction(productId, action) {
    fetch(`/my-app/page/Cart.php?action=${action}&id=${productId}&ajax=1`)
        .then(res => res.json())
        .then(data => {

            const cartCounter = document.querySelector('.cart.counter');

            if (cartCounter) {
                cartCounter.textContent = data.count;
            }

            document.getElementById('header-count').textContent = data.count;
            document.getElementById('total-price').textContent = data.total;

            const itemRow = document.getElementById('item-' + productId);

            if (data.removed) {
                itemRow.remove();
            } else {
                itemRow.querySelector('.item-quantity').textContent = data.quantity;
                itemRow.querySelector('.item-price').textContent = data.rowPrice;
            }

            if (data.count === 0) {
                document.getElementById('cart-content').style.display = 'none';
                document.getElementById('empty-msg').style.display = 'block';
            }
        })
        .catch(err => console.log(err));

}

</script>

</body>
</html>