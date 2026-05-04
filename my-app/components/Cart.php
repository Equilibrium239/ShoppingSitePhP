<?php 
session_start();
require_once(__DIR__ . '/../Models/Database.php');
$db = new Database();

if (isset($_GET['action']) && $_GET['action'] == "add") {
    $id = $_GET['id'];

    if (isset($_SESSION['cart'])) {
        $_SEESSION['cart'] = [];
    }

    $_SEESSION['cart'][] = $id;

    header("Location: Cart.php");
    exit();
}

if (isset($_GET['action']) && !empty($_SEESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $id) {
        $product = $db->getProduct($id);
        if ($product) {
            $product['cart_key'] = $key;
            $cart_items[] = $product;
        }
    }
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>
<body>
    
</body>
</html>