<?php 
session_start();
require_once(__DIR__ . '/../Models/Database.php');
require_once(__DIR__ . '/../Models/CartLogic.php');
require_once(__DIR__ . '/../Models/CartItem.php');

$db = new Database();
$cart = new Cart($db, session_id());
$cartItems = $cart->getItems();

if (empty($cartItems)) {
    header('Location: Cloths.php');
    exit();
}

$checkout_items = [];
$totalPrice = 0;

foreach ($cartItems as $item) {
    $checkout_items[] = $item;
    $totalPrice += $item->rowPrice;
}

// Fraktzoner
$shippingZones = [
    'SE_ZON_1'     => ['namn' => 'Götaland & Svealand (Södra/Mellersta Sverige)', 'avgift' => 79.00],
    'SE_ZON_2'     => ['namn' => 'Nedre Norrland (Kust & Inland)',                'avgift' => 119.00],
    'SE_ZON_3'     => ['namn' => 'Övre Norrland & Glesbygd',                      'avgift' => 159.00],
    'NO_DK_NORDIC' => ['namn' => 'Danmark & Norge (Grannländer)',                  'avgift' => 249.00],
    'EU_ZON_1'     => ['namn' => 'Europa Standard (Tyskland, Benelux, Frankrike)', 'avgift' => 299.00],
];

$selectedZone = $_GET['zone'] ?? '';
$shippingCost = 0.0;
$shippingName = null;

if (isset($shippingZones[$selectedZone])) {
    $shippingName = $shippingZones[$selectedZone]['namn'];
    // Validera mot serverns pris, inte URL-parametern
    $shippingCost = $shippingZones[$selectedZone]['avgift'];
    // Kolla fri frakt (samma gränser som i Cart.php)
    $friGranser = [
        'SE_ZON_1'     => 999.00,
        'SE_ZON_2'     => 1499.00,
        'SE_ZON_3'     => 2499.00,
        'NO_DK_NORDIC' => 3500.00,
        'EU_ZON_1'     => 5000.00,
    ];
    if ($totalPrice >= $friGranser[$selectedZone]) {
        $shippingCost = 0.0;
    }
}

$grandTotal = $totalPrice + $shippingCost;
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f4f4;
    margin: 0;
}

.checkout-layout {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 30px;
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 20px;
}

.checkout-box {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.checkout-box h2 {
    margin-top: 0;
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #fafafa;
}

.summary-item img {
    width: 50px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
}

.subtotal-row {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
    color: #555;
    font-size: 0.95rem;
    padding-bottom: 8px;
}

.shipping-row {
    display: flex;
    justify-content: space-between;
    margin-top: 8px;
    color: #555;
    font-size: 0.95rem;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}

.total-row {
    display: flex;
    justify-content: space-between;
    font-size: 1.3rem;
    font-weight: 800;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #eee;
}

.no-zone-warning {
    background: #fff3f3;
    border: 1px solid #ffcdd2;
    border-radius: 8px;
    padding: 12px 15px;
    color: #c62828;
    font-size: 0.88rem;
    margin-top: 15px;
    text-align: center;
}

.free-shipping-badge {
    display: inline-block;
    background: #e8f5e9;
    color: #2e7d32;
    font-size: 0.75rem;
    padding: 2px 8px;
    border-radius: 20px;
    margin-left: 6px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    font-size: 0.9rem;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 0.95rem;
}

#cardFields {
    margin-top: 20px;
    padding: 20px;
    background: #fafafa;
    border-radius: 10px;
    border: 1px solid #eee;
}

.btn-pay {
    width: 100%;
    padding: 15px;
    background: #222;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    margin-top: 20px;
}

.btn-pay:hover:not(:disabled) {
    background: #007bff;
}

.btn-pay:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.back-to-cart {
    display: block;
    text-align: center;
    margin-top: 12px;
    color: #555;
    font-size: 0.88rem;
    text-decoration: none;
}

.back-to-cart:hover {
    color: #007bff;
}

@media (max-width: 800px) {
    .checkout-layout {
        grid-template-columns: 1fr;
    }
}
</style>

</head>
<body>
    <?php require_once(__DIR__ . '/../components/Header.php'); ?>

    <form action="../page/process_checkout.php" method="POST">

        <!-- Hidden fraktfält till process_checkout.php -->
        <input type="hidden" name="shipping_zone"  value="<?php echo htmlspecialchars($selectedZone); ?>">
        <input type="hidden" name="shipping_cost"  value="<?php echo $shippingCost; ?>">
        <input type="hidden" name="shipping_name"  value="<?php echo htmlspecialchars($shippingName ?? ''); ?>">
        <input type="hidden" name="grand_total"    value="<?php echo $grandTotal; ?>">

        <div class="checkout-layout">

            <!-- Vänster: Leveransinformation & betalning -->
            <div class="checkout-box">

                <h2>Delivery Information</h2>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullName" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="123 Main Street" required>
                </div>

                <div style="display:flex; gap:10px;">
                    <div class="form-group" style="flex:1;">
                        <label>Postnumber</label>
                        <input type="text" name="zip" placeholder="123 45" required>
                    </div>
                    <div class="form-group" style="flex:2;">
                        <label>City</label>
                        <input type="text" name="city" placeholder="Stockholm" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="paymentMethod" id="paymentMethod" onchange="toggleCardFields()">
                        <option value="card">Card (Visa/Mastercard)</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>

                <div id="cardFields">
                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" name="cardNumber" placeholder="1234 5678 9012 3456">
                    </div>
                    <div style="display:flex; gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <label>Expiry Date</label>
                            <input type="text" name="expiry" placeholder="MM/YY">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>CVV</label>
                            <input type="text" name="cvv" placeholder="123">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Name on Card</label>
                        <input type="text" name="cardName" placeholder="John Doe">
                    </div>
                </div>

            </div>

            <!-- Höger: Ordersammanfattning -->
            <div class="checkout-box">

                <h2>Your order</h2>

                <?php foreach ($checkout_items as $item): ?>
                    <div class="summary-item">
                        <div style="display:flex; gap:15px; align-items:center;">
                            <img src="/my-app/image/<?php echo htmlspecialchars($item->imageUrl ?? ''); ?>" alt="">
                            <div>
                                <div style="font-weight:600;">
                                    <?php echo htmlspecialchars($item->productName); ?>
                                </div>
                                <div style="font-size:0.8rem; color:#777;">
                                    Antal: <?php echo $item->quantity; ?>
                                </div>
                            </div>
                        </div>
                        <div style="font-weight:600; white-space:nowrap;">
                            <?php echo number_format($item->rowPrice, 2); ?> USD
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Delsumma -->
                <div class="subtotal-row">
                    <span>Subtotal</span>
                    <span><?php echo number_format($totalPrice, 2); ?> USD</span>
                </div>

                <!-- Fraktrad -->
                <?php if ($shippingName): ?>
                    <div class="shipping-row">
                        <span>
                            Frakt
                            <br>
                            <small style="color:#999; font-size:0.78rem;">
                                <?php echo htmlspecialchars($shippingName); ?>
                            </small>
                        </span>
                        <span style="white-space:nowrap;">
                            <?php if ($shippingCost > 0): ?>
                                <?php echo number_format($shippingCost, 2); ?> USD
                            <?php else: ?>
                                <span style="color:#2e7d32; font-weight:600;">Gratis</span>
                                <span class="free-shipping-badge">✓ Fri frakt</span>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php else: ?>
                    <div class="no-zone-warning">
                        ⚠️ Ingen fraktzon vald.<br>
                        <a href="Cart.php" style="color:#c62828; font-weight:600;">Gå tillbaka och välj fraktzon</a>
                    </div>
                <?php endif; ?>

                <!-- Totalpris -->
                <div class="total-row">
                    <span>Total</span>
                    <span><?php echo number_format($grandTotal, 2); ?> USD</span>
                </div>

                <p style="font-size:0.8rem; color:#999; margin-top:20px; text-align:center;">
                    30-day money back guarantee.
                </p>

                <button type="submit" class="btn-pay" <?php echo !$shippingName ? 'disabled' : ''; ?>>
                    Confirm Purchase
                </button>

                <?php if (!$shippingName): ?>
                    <a href="Cart.php" class="back-to-cart">← Tillbaka till kundvagnen för att välja frakt</a>
                <?php endif; ?>

            </div>

        </div>

    </form>

    <?php require_once(__DIR__ . '/../components/footer.php'); ?>

<script>
function toggleCardFields() {
    const method = document.getElementById('paymentMethod').value;
    document.getElementById('cardFields').style.display = method === 'card' ? 'block' : 'none';
}
</script>

</body>
</html>