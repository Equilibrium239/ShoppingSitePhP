<?php 
session_start();
require_once(__DIR__ . '/../../Models/Database.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $db = new Database();
    $admin = $db->getAdmin($username);
}

if (isset($_SESSION["admin_logged_in"]))

?>