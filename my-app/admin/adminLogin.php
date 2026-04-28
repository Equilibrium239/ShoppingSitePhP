<?php
session_start();
require_once(__DIR__ . '/../Models/Database.php');

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new Database();
    $admin = $db->getAdmin($username); 

    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Fel inloggning!";
    }
}


if (isset($_SESSION['admin_logged_in'])): 


    if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}
?>
    <h1>Välkommen till Admin-panelen, <?php echo $_SESSION['admin_user']; ?>!</h1>
    <p>Här kan du snart lägga till och ta bort produkter.</p>
    <a href="logout.php">Logga ut</a>

<?php else: ?>
    <form method="post" action="">
        <h2>Admin Login</h2>
        <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Användarnamn" required><br><br>
        <input type="password" name="password" placeholder="Lösenord" required><br><br>
        <button type="submit">Logga in</button>
    </form>
<?php endif; ?>