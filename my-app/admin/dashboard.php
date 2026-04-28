<?php 
session_start();

require_once(__DIR__ . '/../Models/Database.php');
$db = new Database();

if (isset($_GET['delete'])) {
    $db->deleteProduct($_GET['delete']);
    header('Location: dashboard.php');

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .form-section {
            background: lightgray;
            padding: 20px;
            border-radius: 5px;
        }

    </style>
<body>
    <h1>Admin Dashboard</h1>
    <a href="components/adminLogin.php?logout=1">Logout</a>

    <div class="form-section">
        <h2>Lägg till ny produkt</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Produktnamn" required>
            <input type="text" name="size" placeholder="Storlek" required>
            <input type="text" name="description" placeholder="Beskrivning" required>
            <input type="text" name="imageUrl" placeholder="Bild URL" required>
            <input type="text" name="price" placeholder="Pris" required>
            <select name="category_id">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat["id"]; ?>"><?php echo $cat["category_name"]; ?></option>
                    <?php endforeach; ?>
            </select>
            <button type="submit" name="add_product">Lägg till produkt</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Namn</th>
            <th>Storlek</th>
            <th>Beskrivning</th>
            <th>Bild URL</th>
            <th>Pris</th>
            <th>Kategori</th>
            <th>Åtgärder</th>
        </tr>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td><?php echo $p['name']; ?></td>
            <td><?php echo $p['size']; ?></td>
            <td><?php echo $p['description']; ?></td>
            <td><?php echo $p['imageUrl']; ?></td>
            <td><?php echo $p['price']; ?> USD</td>
            <td><?php echo $p['category_name']; ?></td>
            <td>
                <a href="dashboard.php?delete=<?php echo $p['id']; ?>" 
                onclick="return confirm('Är du säker?')">Ta bort</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
</body>
</html>