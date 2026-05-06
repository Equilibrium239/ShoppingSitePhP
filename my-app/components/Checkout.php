<?php 
session_start();
require_once(__DIR__ . '/../Models/Database.php');

$db = new Database();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: Cloths.php');
    exit();
}

$checkout_items = [];
$totalPrice = 0;

foreach ($_SESSION['cart'] as $id) {
    $product = $db->getProduct($id);
    if ($product) {
        $checkout_items[] = $product;
        $totalPrice += $product['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

<style>
        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
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

        /* Order Summary Styling */
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

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.3rem;
            font-weight: 800;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
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
            transition: 0.3s;
            margin-top: 20px;
        }

        .btn-pay:hover {
            background: #007bff;
        }

        @media (max-width: 800px) {
            .checkout-layout { grid-template-columns: 1fr; }
        }
    </style>

</head>
<body>
    <?php require_once(__DIR__ . '/Header.php'); ?>

    <div class="checkout-layout">

    
    <div class="checkout-box">
        <h2>Delivery Information</h2>
        <form action="process_checkout.php" method="POST">
            <div class="form-group">
                <label>FullName</label>
                <input type="text" name="fullName" placeholder=" e.g., John Doe" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" placeholder=" e.g., 123 Main St, City, Country" required>
            </div>
            <div style="display: flex; gap: 10px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Postnumber</label>
                        <input type="text" name="zip" placeholder="123 45" required>
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <label>City</label>
                        <input type="text" name="city" placeholder="Stockholm" required>
                    </div>
                    <div class="form-group">
                    <label>PayMethod</label>
                    <select class="form-group" style="width:100%; padding: 12px; border-radius:6px; border: 1px solid #ddd;">
                        <option>Card (Visa/Mastercard)</option>
                        <option>Klarna</option>
                        <option>Swish</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-pay">Confirm Purchase</button>
        </form>
    </div>
    <div class="checkout-box">
            <h2>Your order</h2>
            <?php foreach ($checkout_items as $item): ?>
                <div class="summary-item">
                    <div style="display: flex; gap: 15px;">
                        <img src="/my-app/image/<?php echo $item['imageUrl']; ?>" alt="">
                        <div>
                            <div style="font-weight: 600;"><?php echo $item['name']; ?></div>
                            <div style="font-size: 0.8rem; color: #777;">Size: <?php echo $item['size']; ?></div>
                        </div>
                    </div>
                    <div style="font-weight: 600;"><?php echo $item['price']; ?> USD</div>
                </div>
            <?php endforeach; ?>

            <div class="total-row">
                <span>Total</span>
                <span><?php echo $totalPrice; ?> USD</span>
            </div>
            
            <p style="font-size: 0.8rem; color: #999; margin-top: 20px; text-align: center;">
                Free shipping & 30-day money back guarantee.
            </p>
        </div>

    </div>

    <?php require_once(__DIR__ . '/footer.php'); ?>



    </div>
</body>
</html>