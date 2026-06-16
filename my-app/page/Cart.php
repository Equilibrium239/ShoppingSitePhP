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

$cartItems   = $cart->getItems();
$totalPrice  = $cart->getTotalPrice();
$itemsCount  = $cart->getItemsCount();

// Fraktzoner från CSV
$shippingZones = [
    ['kod' => 'SE_ZON_1',    'namn' => 'Götaland & Svealand (Södra/Mellersta Sverige)', 'avgift' => 79.00,  'fri_frakt' => 999.00],
    ['kod' => 'SE_ZON_2',    'namn' => 'Nedre Norrland (Kust & Inland)',                'avgift' => 119.00, 'fri_frakt' => 1499.00],
    ['kod' => 'SE_ZON_3',    'namn' => 'Övre Norrland & Glesbygd',                      'avgift' => 159.00, 'fri_frakt' => 2499.00],
    ['kod' => 'NO_DK_NORDIC','namn' => 'Danmark & Norge (Grannländer)',                  'avgift' => 249.00, 'fri_frakt' => 3500.00],
    ['kod' => 'EU_ZON_1',    'namn' => 'Europa Standard (Tyskland, Benelux, Frankrike)', 'avgift' => 299.00, 'fri_frakt' => 5000.00],
];
?>

<!DOCTYPE html>
<html lang="sv">
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

        /* Frakt-sektion */
        .shipping-section { margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px; border: 1px solid #eee; }
        .shipping-section label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.95rem; }
        .shipping-section select { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ddd; font-size: 0.9rem; background: white; cursor: pointer; }
        .shipping-cost-row { display: flex; justify-content: space-between; margin-top: 10px; font-size: 0.9rem; color: #555; }
        .free-shipping-notice { color: #4CAF50; font-size: 0.82rem; margin-top: 6px; }
        .divider { border: none; border-top: 1px solid #eee; margin: 15px 0; }
        .grand-total { display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; }
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

        <div class="total">Delsumma: <span id="total-price"><?php echo $totalPrice; ?></span> USD</div>

        <!-- Fraktsektion -->
        <div class="shipping-section">
            <label for="shipping-zone">Välj leveranszon</label>
            <select id="shipping-zone" onchange="updateShipping()">
                <option value="" data-cost="0" data-fri="0">— Välj zon —</option>
                <?php foreach ($shippingZones as $zon): ?>
                    <option
                        value="<?php echo $zon['kod']; ?>"
                        data-cost="<?php echo $zon['avgift']; ?>"
                        data-fri="<?php echo $zon['fri_frakt']; ?>"
                    >
                        <?php echo htmlspecialchars($zon['namn']); ?> — <?php echo number_format($zon['avgift'], 2); ?> USD
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="shipping-cost-row">
                <span>Frakt</span>
                <span id="shipping-cost-display">— USD</span>
            </div>
            <div id="free-shipping-notice" class="free-shipping-notice" style="display:none;"></div>
        </div>

        <hr class="divider">
        <div class="grand-total">
            <span>Totalt</span>
            <span id="grand-total-display">— USD</span>
        </div>

        <a href="Checkout.php?zone=<?php /* skickas via JS */ ?>"
           id="checkout-btn"
           style="display: block; text-align: center; padding: 15px; background: #222; color: white; border-radius: 8px; margin-top: 20px; font-weight: 600; text-decoration: none;">
            Go to Checkout
        </a>

        <a href="Cloths.php" class="back-link">Continue Shopping</a>

    </div>
</div>

<script>
// Baspris från PHP (uppdateras vid add/remove)
let baseTotal = <?php echo json_encode((float)$totalPrice); ?>;

function updateShipping() {
    const select = document.getElementById('shipping-zone');
    const chosen = select.options[select.selectedIndex];
    const cost   = parseFloat(chosen.dataset.cost) || 0;
    const friGrans = parseFloat(chosen.dataset.fri) || 0;

    const isFree = friGrans > 0 && baseTotal >= friGrans;
    const actualCost = isFree ? 0 : cost;
    const grandTotal = baseTotal + actualCost;

    document.getElementById('shipping-cost-display').textContent =
        select.value === '' ? '— USD' : actualCost.toFixed(2) + ' USD';

    const notice = document.getElementById('free-shipping-notice');
    if (select.value === '') {
        notice.style.display = 'none';
    } else if (isFree) {
        notice.textContent = '✓ Fri frakt uppnådd!';
        notice.style.display = 'block';
    } else {
        const kvar = (friGrans - baseTotal).toFixed(2);
        notice.textContent = `Handla för ${kvar} USD mer för fri frakt`;
        notice.style.display = 'block';
    }

    document.getElementById('grand-total-display').textContent =
        select.value === '' ? '— USD' : grandTotal.toFixed(2) + ' USD';

    // Uppdatera checkout-länken med vald zon
    const btn = document.getElementById('checkout-btn');
    if (select.value) {
        btn.href = `Checkout.php?zone=${encodeURIComponent(select.value)}&shipping=${actualCost.toFixed(2)}`;
    } else {
        btn.href = 'Checkout.php';
    }
}

function cartAction(productId, action) {
    fetch(`/my-app/page/Cart.php?action=${action}&id=${productId}&ajax=1`)
        .then(res => res.json())
        .then(data => {
            const cartCounter = document.querySelector('.cart.counter');
            if (cartCounter) cartCounter.textContent = data.count;

            document.getElementById('header-count').textContent = data.count;
            document.getElementById('total-price').textContent = parseFloat(data.total).toFixed(2);

            // Uppdatera baseTotal och räkna om frakt
            baseTotal = parseFloat(data.total);
            updateShipping();

            const itemRow = document.getElementById('item-' + productId);
            if (data.removed) {
                itemRow.remove();
            } else {
                itemRow.querySelector('.item-quantity').textContent = data.quantity;
                itemRow.querySelector('.item-price').textContent = parseFloat(data.rowPrice).toFixed(2);
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