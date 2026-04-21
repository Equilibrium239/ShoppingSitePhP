<?php 

require_once(__DIR__ . '/../Models/Database.php');

if (!isset($_GET['id'])) {
    die("Ingen ID angavs.");  
}

$id = $_GET['id'];
$database = new Database();
$product = $database->getProduct($id);

if (!$product) {
    die("Produkt inte hittad.");  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
</head>
    <style>
        .product-detail {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .product-detail img {
            max-width: 400px;
        }

        .price {
            font-weight: bold;
            color: green;
            font-size: 1.5rem;
        }
    </style>
<body>

    <a href="../Cloths.php">Tillbaka till kläder</a>

    <div class="product-detail">
        <div class="image-section">
            <img src="../image/<?php echo $product['imageUrl']; ?>" alt="<?php echo $product['name']; ?>">
        </div>

        <div class="info-section">
            <h1><?php echo $product['name']; ?></h1>
            <p><strong>Storlek:</strong> <?php echo $product['size']; ?></p>
            <p><strong>Beskrivning:</strong> <?php echo $product['description']; ?></p>
            <p class="price"><?php echo $product['price']; ?> USD</p>
            <button>Lägg i varukorg</button>

        </div>

    </div>
    
</body>
</html>

