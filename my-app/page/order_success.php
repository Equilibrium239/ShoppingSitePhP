<?php
session_start();
require_once(__DIR__ . '/../Models/Database.php');
require_once(__DIR__ . '/../Models/CartItem.php');
require_once(__DIR__ . '/../Models/CartLogic.php');
require_once __DIR__ . '/../../vendor/autoload.php';

// Clear the cart after successful payment
$db   = new Database();
$cart = new Cart($db, session_id());
$cart->clearCart();

$paymentIntentId = htmlspecialchars($_GET['pi'] ?? '');
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed!</title>
    <link rel="stylesheet" href="/my-app/src/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 60vh;
        }
        .success-box {
            background: white;
            padding: 50px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 480px;
            width: 100%;
        }
        .checkmark {
            font-size: 4rem;
            margin-bottom: 10px;
        }
        h1 { color: #2e7d32; margin-bottom: 10px; }
        p  { color: #555; }
        .pi-id {
            font-size: 0.75rem;
            color: #aaa;
            margin-top: 20px;
            word-break: break-all;
        }
        .btn-home {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 28px;
            background: #222;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn-home:hover { background: #007bff; }
    </style>
</head>
<body>
    <div class="success-box">
        <div class="checkmark">✅</div>
        <h1>Order Confirmed!</h1>
        <p>Thank you for your purchase. Your payment was processed successfully.</p>
        <?php if ($paymentIntentId): ?>
            <p class="pi-id">Payment ID: <?php echo $paymentIntentId; ?></p>
        <?php endif; ?>
        <a href="/index.php" class="btn-home">Continue Shopping</a>
    </div>
</body>
</html>
