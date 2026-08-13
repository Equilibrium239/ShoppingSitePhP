<?php
session_start();
require_once(__DIR__ . '/../Models/Database.php');

$database = new Database();
$auth = $database->getUsersDatabase()->getAuth();

// Log the user out
if ($auth->isLoggedIn()) {
    $auth->logOut();
}

// Redirect to home page
header('Location: /index.php');
exit;
