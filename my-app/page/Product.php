<?php
require_once(__DIR__ . '/../Models/Database.php');

if (!isset($_GET['id'])) {
    header('Location: /my-app/page/Cloths.php');
    exit();
}

$db = new Database();
// Init auth before any HTML output — Header.php reuses $db so no second Auth instance is created
$db->getUsersDatabase()->getAuth();

$id      = (int) $_GET['id'];
$product = $db->getProduct($id);

if (!$product) {
    header('Location: /my-app/page/Cloths.php');
    exit();
}
$database = $db; // alias kept for any references below
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> — Sneak Store</title>
    <link rel="stylesheet" href="/my-app/src/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }

        body {
            background: #f6f6f6;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .breadcrumb {
            max-width: 1100px;
            margin: 20px auto 0;
            padding: 0 24px;
            font-size: 0.82rem;
            color: #999;
        }

        .breadcrumb a {
            color: #999;
            text-decoration: none;
        }

        .breadcrumb a:hover { color: #007bff; }
        .breadcrumb span { margin: 0 6px; }

        /* ── Main layout ── */
        .product-page {
            max-width: 1100px;
            margin: 20px auto 60px;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: start;
        }

        /* ── Image side ── */
        .product-image-wrap {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            aspect-ratio: 4/5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Info side ── */
        .product-details {
            background: white;
            border-radius: 16px;
            padding: 36px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        }

        .product-category {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #999;
            margin-bottom: 10px;
        }

        .product-details h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #111;
            text-transform: uppercase;
            margin: 0 0 6px;
            letter-spacing: 1px;
        }

        .product-price {
            font-size: 1.8rem;
            font-weight: 800;
            color: #111;
            margin: 16px 0 24px;
        }

        .divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
            color: #444;
        }

        .detail-row span:first-child {
            color: #999;
            font-weight: 500;
        }

        .detail-row span:last-child {
            font-weight: 600;
            color: #111;
        }

        .product-description {
            font-size: 0.92rem;
            color: #666;
            line-height: 1.7;
            margin: 20px 0 28px;
        }

        /* ── Buttons ── */
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 28px;
        }

        .btn-cart {
            width: 100%;
            padding: 16px;
            background: #111;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.25s;
            letter-spacing: 0.5px;
        }

        .btn-cart:hover:not(:disabled) { background: #007bff; }
        .btn-cart:disabled { opacity: 0.6; cursor: not-allowed; }

        .btn-back {
            display: block;
            text-align: center;
            padding: 13px;
            border: 2px solid #ddd;
            border-radius: 8px;
            color: #555;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: border-color 0.2s, color 0.2s;
        }

        .btn-back:hover {
            border-color: #111;
            color: #111;
        }

        #cart-feedback {
            text-align: center;
            font-size: 0.85rem;
            color: #2e7d32;
            min-height: 20px;
            font-weight: 600;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .product-page {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>

<?php require_once(__DIR__ . '/../components/header.php'); ?>

<!-- Breadcrumb -->
<nav class="breadcrumb">
    <a href="/my-app/page/Cloths.php">Catalogue</a>
    <span>›</span>
    <?php echo htmlspecialchars($product['name']); ?>
</nav>

<!-- Product page -->
<div class="product-page">

    <!-- Left: Image -->
    <div class="product-image-wrap">
        <img src="/my-app/image/<?php echo htmlspecialchars($product['imageUrl']); ?>"
             alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>

    <!-- Right: Details -->
    <div class="product-details">

        <p class="product-category">
            <i class="fa-solid fa-tag" style="margin-right:4px;"></i>
            <?php echo htmlspecialchars($product['category_name'] ?? 'Clothing'); ?>
        </p>

        <h1><?php echo htmlspecialchars($product['name']); ?></h1>

        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>

        <hr class="divider">

        <div class="detail-row">
            <span>Size</span>
            <span><?php echo htmlspecialchars($product['size']); ?></span>
        </div>

        <p class="product-description">
            <?php echo htmlspecialchars($product['description']); ?>
        </p>

        <div class="btn-group">
            <p id="cart-feedback"></p>
            <button class="btn-cart" id="add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                <i class="fa-solid fa-cart-plus"></i> Add to Cart
            </button>
            <a href="/my-app/page/Cloths.php" class="btn-back">
                ← Back to Catalogue
            </a>
        </div>

    </div>

</div>

<?php require_once(__DIR__ . '/../components/footer.php'); ?>

<script>
function addToCart(productId) {
    const btn      = document.getElementById('add-to-cart-btn');
    const feedback = document.getElementById('cart-feedback');

    btn.disabled = true;
    btn.innerHTML = 'Adding…';

    fetch(`/my-app/page/Cart.php?action=add&id=${productId}&ajax=1`)
        .then(res => res.json())
        .then(data => {
            const counter = document.querySelector('.cart.counter');
            if (counter) counter.textContent = data.count;

            feedback.textContent = '✓ Added to cart!';
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Added';

            setTimeout(() => {
                btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Add to Cart';
                btn.disabled  = false;
                feedback.textContent = '';
            }, 2000);
        })
        .catch(() => {
            btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Add to Cart';
            btn.disabled  = false;
        });
}
</script>

</body>
</html>
