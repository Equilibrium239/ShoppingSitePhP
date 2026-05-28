<?php
require_once(__DIR__ . '/../Models/Database.php');
require_once(__DIR__ . '/../Models/CartLogic.php');
require_once(__DIR__ . '/../Models/CartItem.php');

$database = new Database();
$cart = new Cart($database, session_id());
$cartCount = $cart->getItemsCount();
?>

<header class="main-header">

    <nav class="navbar" style="display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">

        <div class="logo">
            <a href="/index.php" style="text-decoration: none; color: inherit; font-weight: bold;">
                Sneak Store
            </a>
        </div>

        <div class="nav-search" style="flex-grow: 0.5; margin: 0 20px;">

            <form action="/my-app/page/Cloths.php" method="GET" style="display: flex; width: 100%;">

                <input
                    type="text"
                    name="search"
                    placeholder="Sök produkter..."
                    style="width: 100%; padding: 8px 12px; border-radius: 20px 0 0 20px; border: 1px solid #ccc; outline: none;"
                >

                <button
                    type="submit"
                    style="padding: 8px 15px; border-radius: 0 20px 20px 0; border: 1px solid #ccc; border-left: none; background: #222; color: white; cursor: pointer;"
                >
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

            </form>

        </div>

        <ul class="nav-menu" style="display: flex; list-style: none; gap: 20px; align-items: center; margin: 0;">

            <li>
                <a href="/my-app/page/Cloths.php" class="nav-link">
                    Cloths
                </a>
            </li>

            <li>
                <a href="/my-app/page/Cart.php" class="cart-wrapper">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="cart counter"><?php echo $cartCount; ?></span>
                </a>
            </li>

            <li>
                <a href="/my-app/page/Medlemskap.php">
                    <button class="Btn" style="margin: 0;">
                        Medlem
                    </button>
                </a>
            </li>

        </ul>

    </nav>

</header>