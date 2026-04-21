<?php 

require_once(__DIR__ . '/../Models/Database.php');


$id = $_GET["id"];

echo "Du klickade på Product id: " . $id;


$database = new Database();


$currentProduct = $database->getProduct($id);
?>

<h1>Du har tryckt på <?php echo $currentProduct['name']; ?>:</h1>
<p>Pris: <?php echo $currentProduct['price']; ?> USD</p>