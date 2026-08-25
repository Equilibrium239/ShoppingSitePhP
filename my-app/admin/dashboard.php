<?php 
session_start();

// ── Admin auth guard ──────────────────────────────────────────────────────────
// Only allow access if the admin is logged in via session.
// To log in, POST username + password to this page.
// Credentials are checked against the `admin` table in the database.

require_once(__DIR__ . '/../Models/Database.php');
$db = new Database();

$loginError = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $admin = $db->getAdmin($username);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username']  = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        $loginError = 'Invalid username or password.';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    $_SESSION['admin_logged_in'] = false;
    session_destroy();
    header('Location: dashboard.php');
    exit();
}

// Block access if not logged in — show login form instead
if (empty($_SESSION['admin_logged_in'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 320px; }
        h2 { margin-top: 0; }
        input { width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 0.95rem; }
        button { width: 100%; padding: 12px; background: #222; color: white; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #007bff; }
        .error { color: #dc2626; font-size: 0.88rem; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if ($loginError): ?>
            <p class="error"><?php echo htmlspecialchars($loginError); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text"     name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="admin_login">Login</button>
        </form>
    </div>
</body>
</html>
<?php
    exit(); // Stop here — don't render the dashboard
}
// ── End auth guard ────────────────────────────────────────────────────────────

if (isset($_GET['delete'])) {
    $db->deleteProduct((int)$_GET['delete']);
    header('Location: dashboard.php');
    exit();
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
    <a href="dashboard.php?logout=1">Logout</a>

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