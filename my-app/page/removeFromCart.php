<?php

require_once(__DIR__.'/../Models/Database.php');
require_once(__DIR__.'/../Models/Cart.php');
require_once(__DIR__.'/../Models/CartItem.php');

$productIdToAddToCart = $_GET['id'];

$database = new Database();
$cart = new Cart($database, session_id());

// in princip = insert or update cartitem set 
//  = quantity + 1 where sessionId = session_id and productId = $productIdToAddToCart
// inte bara i databasen utan även i cartItems arrayen i Cart klassen
$cart->removeItem($productIdToAddToCart, 1);


echo "Removing from cart..." . $productIdToAddToCart;
$fromPage = urldecode($_GET['fromPage'] ?? '/');
echo $fromPage; // /   (category?id=1)
header("Location: $fromPage");


?>