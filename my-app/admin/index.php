<?php 
session_start();
require_once(__DIR__ . '/../../Models/Database.php');

if (isset($_GET['delete'])) {
    $db->deleteProduct($_GET['delete']);
   // header()
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["add_product"])) {
    $db->addProduct(
        $_POST["name"], 
        $_POST["size"], 
        $_POST["description"], 
        $_POST["imageUrl"], 
        $_POST["price"], 
        $_POST["category_id"]);
}

$products = $db->getAllProducts();
$categories = $db->getAllCategories();

?>

