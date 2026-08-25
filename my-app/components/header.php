<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../Models/Database.php');
require_once(__DIR__ . '/../Models/CartLogic.php');
require_once(__DIR__ . '/../Models/CartItem.php');

// Reuse an existing $db instance if the including page already created one,
// otherwise create a new one. This prevents a second Auth instantiation which
// would try to send headers after HTML output has already started.
if (!isset($db)) {
    $db = new Database();
}
$database = $db;

$cart = new Cart($database, session_id());
$cartCount = $cart->getItemsCount();

// Check if user is logged in
$auth = $database->getUsersDatabase()->getAuth();
$isLoggedIn = $auth->isLoggedIn();
$userEmail = '';
$userInitial = '';

if ($isLoggedIn) {
    $userEmail = $auth->getEmail();
    $userInitial = strtoupper(substr($userEmail, 0, 1)); // First letter, uppercase
}
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

            <?php if ($isLoggedIn): ?>
                <!-- User is logged in: show bubble + logout -->
                <li>
                    <div class="user-bubble" title="<?php echo htmlspecialchars($userEmail); ?>">
                        <?php echo $userInitial; ?>
                    </div>
                </li>
                <li>
                    <a href="/my-app/page/logout.php">
                        <button class="Btn" style="margin: 0; background: #dc2626;">
                            Logga ut
                        </button>
                    </a>
                </li>
            <?php else: ?>
                <!-- User is NOT logged in: show Medlem button -->
                <li>
                    <a href="/my-app/page/Medlemskap.php">
                        <button class="Btn" style="margin: 0;">
                            Medlem
                        </button>
                    </a>
                </li>
            <?php endif; ?>

        </ul>

    </nav>

</header>

<style>
/* User bubble styling (like Webhallen) */
.user-bubble {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: transform 0.2s, box-shadow 0.2s;
}

.user-bubble:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
}
</style>