<?php

require_once(__DIR__ . '/../Models/Database.php');

$pdo = new PDO(
    "mysql:host=localhost;dbname=Shopping;charset=utf8mb4",
    "root",
    ""
);

$search = $_GET['search'] ?? '';

$sql = "
SELECT 
    Inventory.*,
    categories.category_name
FROM Inventory
LEFT JOIN categories 
ON Inventory.category_id = categories.id
WHERE 
    Inventory.name LIKE :search
    OR Inventory.description LIKE :search
    OR categories.category_name LIKE :search
ORDER BY Inventory.id DESC
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'search' => "%$search%"
]);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Globalsearch</title>
</head>
<body>
    <div class="container">
        <form method="GET" action="">
            <div class="search-wrap">
                <input type="text" name="search" class="search-box" placeholder="Search product like shoes" value="<?= htmlspecialchars($search) ?>" autofocus>
            </div>
        </form>

        

    </div>
    
</body>
</html>
