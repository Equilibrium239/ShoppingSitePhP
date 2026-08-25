<?php 
    require_once(__DIR__ . '/../Models/Database.php');

    $db = new Database();
    // Init auth before any HTML output to prevent "headers already sent" errors
    $db->getUsersDatabase()->getAuth();

    $sort     = $_GET['sort']     ?? 'default';
    $category = $_GET['category'] ?? null;
    $search   = $_GET['search']   ?? null;

    if ($search) {
        $my_products = $db->searchProducts($search);
    } elseif ($sort == 'popular') {
        $my_products = $db->getPopularProducts(10);
    } else {
        $my_products = $db->getProductsFilterd($category, $sort);
    }

    $categories = $db->getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue — Sneak Store</title>
    <link rel="stylesheet" href="/my-app/src/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }

        body {
            background: #f6f6f6;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        /* ── Page banner ── */
        .catalogue-banner {
            background: #111;
            color: white;
            text-align: center;
            padding: 48px 20px 36px;
        }

        .catalogue-banner h1 {
            font-size: 2.4rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0 0 8px;
        }

        .catalogue-banner p {
            color: #aaa;
            font-size: 0.95rem;
            margin: 0;
        }

        /* ── Search result notice ── */
        .search-notice {
            max-width: 1300px;
            margin: 20px auto 0;
            padding: 0 20px;
            font-size: 0.9rem;
            color: #555;
        }

        .search-notice a {
            color: #007bff;
            text-decoration: none;
            margin-left: 8px;
        }

        /* ── Layout: sidebar + grid ── */
        .catalogue-layout {
            display: flex;
            gap: 30px;
            max-width: 1300px;
            margin: 30px auto;
            padding: 0 20px;
            align-items: flex-start;
        }

        /* ── Sidebar ── */
        .filter-sidebar {
            width: 220px;
            flex-shrink: 0;
            background: white;
            border-radius: 12px;
            padding: 24px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            position: sticky;
            top: 20px;
        }

        .filter-sidebar h3 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #999;
            margin: 0 0 14px;
        }

        .filter-sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0 0 28px;
        }

        .filter-sidebar ul li a {
            display: block;
            padding: 7px 10px;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-size: 0.9rem;
            transition: background 0.2s, color 0.2s;
        }

        .filter-sidebar ul li a:hover,
        .filter-sidebar ul li a.active {
            background: #111;
            color: white;
        }

        .filter-sidebar select {
            width: 100%;
            padding: 9px 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.88rem;
            background: white;
            cursor: pointer;
            color: #333;
        }

        /* ── Product grid ── */
        .catalogue-main {
            flex: 1;
            min-width: 0;
        }

        .results-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.88rem;
            color: #777;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 24px;
        }

        /* ── Card ── */
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            border: 1px solid #f0f0f0;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .product-image {
            width: 100%;
            height: 260px;
            overflow: hidden;
            background: #f9f9f9;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .category-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.55);
            color: white;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .product-info {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            gap: 4px;
        }

        .product-info h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-info .description {
            font-size: 0.78rem;
            color: #888;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin: 2px 0 6px;
        }

        .size-tag {
            display: inline-block;
            background: #f4f4f4;
            color: #666;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
            align-self: flex-start;
        }

        .price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 800;
            color: #111;
        }

        .BtnCloths {
            padding: 8px 14px;
            background: #111;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.25s;
            white-space: nowrap;
        }

        .BtnCloths:hover:not(:disabled) {
            background: #007bff;
        }

        .BtnCloths:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 16px;
            display: block;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .catalogue-layout { flex-direction: column; }
            .filter-sidebar { width: 100%; position: static; }
        }
    </style>
</head>
<body>

<?php require_once(__DIR__ . '/../components/header.php'); ?>

<!-- Banner -->
<div class="catalogue-banner">
    <h1>Our Collection</h1>
    <p><?php echo count($my_products); ?> products available</p>
</div>

<?php if ($search): ?>
    <p class="search-notice">
        Showing results for "<strong><?php echo htmlspecialchars($search); ?></strong>"
        <a href="/my-app/page/Cloths.php">✕ Clear search</a>
    </p>
<?php endif; ?>

<div class="catalogue-layout">

    <!-- Sidebar -->
    <aside class="filter-sidebar">

        <h3>Categories</h3>
        <ul>
            <li>
                <a href="/my-app/page/Cloths.php" class="<?php echo !$category ? 'active' : ''; ?>">
                    All
                </a>
            </li>
            <?php foreach ($categories as $cat): ?>
                <li>
                    <a href="/my-app/page/Cloths.php?category=<?php echo urlencode($cat['category_name']); ?>"
                       class="<?php echo $category === $cat['category_name'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['category_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <h3>Sort by</h3>
        <form method="GET" action="/my-app/page/Cloths.php">
            <?php if ($category): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <?php endif; ?>
            <select name="sort" onchange="this.form.submit()">
                <option value="default"    <?php echo $sort === 'default'    ? 'selected' : ''; ?>>Default</option>
                <option value="price_low"  <?php echo $sort === 'price_low'  ? 'selected' : ''; ?>>Price: Low → High</option>
                <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High → Low</option>
                <option value="name"       <?php echo $sort === 'name'       ? 'selected' : ''; ?>>A → Z</option>
                <option value="popular"    <?php echo $sort === 'popular'    ? 'selected' : ''; ?>>Most Popular</option>
            </select>
        </form>

    </aside>

    <!-- Product grid -->
    <main class="catalogue-main">

        <div class="results-bar">
            <span><?php echo count($my_products); ?> items</span>
            <?php if ($category): ?>
                <span>Filtered by: <strong><?php echo htmlspecialchars($category); ?></strong></span>
            <?php endif; ?>
        </div>

        <?php if (empty($my_products)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-box-open"></i>
                <p>No products found.</p>
                <a href="/my-app/page/Cloths.php" style="color:#007bff;">View all products</a>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($my_products as $product): ?>
                    <div class="product-card" onclick="window.location='/my-app/page/Product.php?id=<?php echo $product['id']; ?>'">
                        <div class="product-image">
                            <img src="/my-app/image/<?php echo htmlspecialchars($product['imageUrl']); ?>"
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 loading="lazy">
                            <span class="category-badge">
                                <?php echo htmlspecialchars($product['category_name'] ?? ''); ?>
                            </span>
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <span class="size-tag">Size: <?php echo htmlspecialchars($product['size']); ?></span>
                            <div class="price-row">
                                <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                                <button class="BtnCloths" onclick="event.stopPropagation(); addToCart(<?php echo $product['id']; ?>, this)">
                                    <i class="fa-solid fa-cart-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

</div>

<?php require_once(__DIR__ . '/../components/footer.php'); ?>

<script>
function addToCart(productId, btn) {
    btn.disabled = true;
    btn.innerHTML = '✓ Added';

    fetch(`/my-app/page/Cart.php?action=add&id=${productId}&ajax=1`)
        .then(res => res.json())
        .then(data => {
            const counter = document.querySelector('.cart.counter');
            if (counter) counter.textContent = data.count;
            setTimeout(() => {
                btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Add';
                btn.disabled = false;
            }, 1500);
        })
        .catch(() => {
            btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Add';
            btn.disabled = false;
        });
}
</script>

</body>
</html>
