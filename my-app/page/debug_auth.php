<?php
session_start();
require_once(__DIR__ . '/../Models/Database.php');

$database = new Database();
$auth = $database->getUsersDatabase()->getAuth();

echo "<h1>Debug - Auth Status</h1>";
echo "<hr>";

echo "<h2>Session Status:</h2>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session started: " . (session_status() === PHP_SESSION_ACTIVE ? 'YES' : 'NO') . "\n";
echo "</pre>";

echo "<h2>Auth Status:</h2>";
echo "<pre>";
echo "Is Logged In: " . ($auth->isLoggedIn() ? 'YES' : 'NO') . "\n";

if ($auth->isLoggedIn()) {
    echo "User Email: " . $auth->getEmail() . "\n";
    echo "User ID: " . $auth->getUserId() . "\n";
    echo "First Letter: " . strtoupper(substr($auth->getEmail(), 0, 1)) . "\n";
} else {
    echo "User is NOT logged in\n";
}
echo "</pre>";

echo "<h2>Session Data:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<hr>";
echo '<p><a href="/my-app/page/Medlemskap.php">Go to Login</a></p>';
echo '<p><a href="/index.php">Go to Home</a></p>';
?>
