<?php
require_once(__DIR__ . '/../Models/Database.php');
require_once(__DIR__ . '/../Models/CartLogic.php');
require_once(__DIR__ . '/../Models/CartItem.php');
require_once __DIR__ . '/../../vendor/autoload.php';

// Load .env so we can pass the publishable key safely to the frontend
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Initialize auth BEFORE any HTML output — delight-im/auth calls session_start()
// internally which sets headers; doing it here prevents "headers already sent" errors.
$db = new Database();
$db->getUsersDatabase()->getAuth();

$stripePublishableKey = $_ENV['STRIPE_PUBLISHABLE_KEY'] ?? '';

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
    $shippingCost = $shippingZones[$selectedZone]['avgift'];
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
    <!-- Stripe.js must be loaded directly from Stripe's CDN -->
    <script src="https://js.stripe.com/v3/"></script>
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
    align-items: center;
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

/* Stripe card element container */
#stripe-card-element {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: white;
    margin-top: 4px;
}

#stripe-card-errors {
    color: #dc2626;
    font-size: 0.85rem;
    margin-top: 8px;
    min-height: 20px;
}

#cardFields {
    margin-top: 20px;
    padding: 20px;
    background: #fafafa;
    border-radius: 10px;
    border: 1px solid #eee;
}

/* Currency conversion block */
.currency-box {
    margin-top: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.currency-box label {
    font-weight: 600;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 8px;
}

.currency-box select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.9rem;
    background: white;
    cursor: pointer;
}

#converted-price-display {
    margin-top: 10px;
    font-size: 1rem;
    color: #333;
    min-height: 24px;
}

#converted-price-display .converted-amount {
    font-weight: 700;
    color: #007bff;
}

#converted-price-display .currency-error {
    color: #dc2626;
    font-size: 0.85rem;
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

.back-to-cart:hover { color: #007bff; }

#payment-processing-msg {
    text-align: center;
    color: #555;
    font-size: 0.9rem;
    margin-top: 10px;
    display: none;
}

@media (max-width: 800px) {
    .checkout-layout { grid-template-columns: 1fr; }
}
</style>
</head>
<body>
    <?php require_once(__DIR__ . '/../components/Header.php'); ?>

    <div class="checkout-layout">

        <!-- LEFT: Delivery + Payment -->
        <div class="checkout-box">

            <h2>Delivery Information</h2>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="fullName" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" id="address" placeholder="123 Main Street" required>
            </div>

            <div style="display:flex; gap:10px;">
                <div class="form-group" style="flex:1;">
                    <label>Postnumber</label>
                    <input type="text" id="zip" placeholder="123 45" required>
                </div>
                <div class="form-group" style="flex:2;">
                    <label>City</label>
                    <input type="text" id="city" placeholder="Stockholm" required>
                </div>
            </div>

            <h2 style="margin-top:30px;">Payment</h2>

            <div id="cardFields">
                <div class="form-group">
                    <label>Card Details (test: use 4242 4242 4242 4242)</label>
                    <!-- Stripe injects a secure iframe here -->
                    <div id="stripe-card-element"></div>
                    <div id="stripe-card-errors"></div>
                </div>
            </div>

            <button id="btn-pay" class="btn-pay" <?php echo !$shippingName ? 'disabled' : ''; ?>>
                Confirm Purchase — $<?php echo number_format($grandTotal, 2); ?>
            </button>
            <p id="payment-processing-msg">Processing payment, please wait…</p>

            <?php if (!$shippingName): ?>
                <a href="Cart.php" class="back-to-cart">← Back to cart to select shipping</a>
            <?php endif; ?>

        </div>

        <!-- RIGHT: Order summary -->
        <div class="checkout-box">

            <h2>Your Order</h2>

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
                        $<?php echo number_format($item->rowPrice, 2); ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="subtotal-row">
                <span>Subtotal</span>
                <span>$<?php echo number_format($totalPrice, 2); ?></span>
            </div>

            <?php if ($shippingName): ?>
                <div class="shipping-row">
                    <span>
                        Frakt<br>
                        <small style="color:#999; font-size:0.78rem;">
                            <?php echo htmlspecialchars($shippingName); ?>
                        </small>
                    </span>
                    <span style="white-space:nowrap;">
                        <?php if ($shippingCost > 0): ?>
                            $<?php echo number_format($shippingCost, 2); ?>
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

            <div class="total-row">
                <span>Total</span>
                <span>$<?php echo number_format($grandTotal, 2); ?></span>
            </div>

            <!-- ── Currency Conversion ── -->
            <div class="currency-box">
                <label for="currency-select">See total in another currency:</label>
                <select id="currency-select">
                    <option value="">-- Select currency --</option>
                    <option value="EUR">EUR — Euro</option>
                    <option value="SEK">SEK — Swedish Krona</option>
                    <option value="GBP">GBP — British Pound</option>
                    <option value="DKK">DKK — Danish Krone</option>
                    <option value="NOK">NOK — Norwegian Krone</option>
                </select>
                <div id="converted-price-display"></div>
            </div>

            <p style="font-size:0.8rem; color:#999; margin-top:20px; text-align:center;">
                30-day money back guarantee. Payment processed securely by Stripe.
            </p>

        </div>

    </div>

    <?php require_once(__DIR__ . '/../components/footer.php'); ?>

<script>
// ── Stripe setup ──────────────────────────────────────────────────────────────
const STRIPE_PUBLISHABLE_KEY = <?php echo json_encode($stripePublishableKey); ?>;
const GRAND_TOTAL_USD = <?php echo json_encode((float)$grandTotal); ?>;

const stripe = Stripe(STRIPE_PUBLISHABLE_KEY);
const elements = stripe.elements();

const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            fontFamily: "'Segoe UI', sans-serif",
            color: '#333',
            '::placeholder': { color: '#aaa' }
        },
        invalid: { color: '#dc2626' }
    }
});
cardElement.mount('#stripe-card-element');

// Show validation errors live as the user types
cardElement.on('change', (event) => {
    const errorEl = document.getElementById('stripe-card-errors');
    errorEl.textContent = event.error ? event.error.message : '';
});

// ── Pay button ────────────────────────────────────────────────────────────────
document.getElementById('btn-pay').addEventListener('click', async () => {
    const btn = document.getElementById('btn-pay');
    const msgEl = document.getElementById('payment-processing-msg');
    const errorEl = document.getElementById('stripe-card-errors');

    // Basic delivery field validation
    const fullName = document.getElementById('fullName').value.trim();
    const address  = document.getElementById('address').value.trim();
    const zip      = document.getElementById('zip').value.trim();
    const city     = document.getElementById('city').value.trim();

    if (!fullName || !address || !zip || !city) {
        errorEl.textContent = 'Please fill in all delivery fields.';
        return;
    }

    btn.disabled = true;
    msgEl.style.display = 'block';
    errorEl.textContent = '';

    try {
        // 1. Create a PaymentIntent on the server
        const response = await fetch('/my-app/rest/create_payment_intent.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ amount: GRAND_TOTAL_USD })
        });

        const data = await response.json();

        if (data.error) {
            errorEl.textContent = data.error;
            btn.disabled = false;
            msgEl.style.display = 'none';
            return;
        }

        // 2. Confirm the payment with Stripe
        const { error, paymentIntent } = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: { name: fullName }
            }
        });

        if (error) {
            errorEl.textContent = error.message;
            btn.disabled = false;
            msgEl.style.display = 'none';
            return;
        }

        if (paymentIntent.status === 'succeeded') {
            // Payment confirmed — redirect to success page
            window.location.href = '/my-app/page/order_success.php?pi=' + paymentIntent.id;
        }

    } catch (err) {
        errorEl.textContent = 'An unexpected error occurred. Please try again.';
        console.error(err);
        btn.disabled = false;
        msgEl.style.display = 'none';
    }
});

// ── Currency Conversion ───────────────────────────────────────────────────────
// Open Exchange Rates — free plan base currency is always USD.
// Formula: Since our prices are in USD, conversion is simple: USD * rates.TARGET
const OER_APP_ID = '0d7347c49e0b4e269dea5344c2450031';
const displayEl  = document.getElementById('converted-price-display');
let cachedRates  = null; // cache so we don't re-fetch on every dropdown change

const currencySymbols = {
    EUR: '€', USD: '$', GBP: '£', DKK: 'kr', NOK: 'kr', SEK: 'kr'
};

async function fetchRates() {
    if (cachedRates) return cachedRates;

    const res = await fetch(`https://openexchangerates.org/api/latest.json?app_id=${OER_APP_ID}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();
    if (json.error) throw new Error(json.description || 'API error');
    cachedRates = json.rates;
    return cachedRates;
}

function convertUSD(rates, targetCurrency) {
    // Prices are in USD, rates are relative to USD base
    // USD → target is simple multiplication
    return GRAND_TOTAL_USD * rates[targetCurrency];
}

async function updateConvertedPrice() {
    const select = document.getElementById('currency-select');
    const target = select.value;

    if (!target) {
        displayEl.innerHTML = '';
        return;
    }

    displayEl.innerHTML = '<span style="color:#999; font-size:0.85rem;">Loading…</span>';

    try {
        const rates = await fetchRates();

        if (!rates[target]) {
            throw new Error(`Currency ${target} not found in API response`);
        }

        const converted = convertUSD(rates, target);
        const symbol    = currencySymbols[target] || target;
        const formatted = new Intl.NumberFormat('sv-SE', {
            minimumFractionDigits: 2, maximumFractionDigits: 2
        }).format(converted);

        displayEl.innerHTML =
            `≈ <span class="converted-amount">${symbol} ${formatted} ${target}</span>
             <span style="font-size:0.75rem; color:#999; margin-left:4px;">(live rate)</span>`;

    } catch (err) {
        console.error('Currency conversion failed:', err);
        displayEl.innerHTML =
            '<span class="currency-error">⚠️ Currency conversion temporarily unavailable.</span>';
    }
}

document.getElementById('currency-select').addEventListener('change', updateConvertedPrice);
</script>

</body>
</html>
