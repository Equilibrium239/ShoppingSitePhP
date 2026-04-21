<?php 
require_once(__DIR__ . '/Product.php');

class Database {
    public $pdo;
    private $usersDatabase;

    function getUsersDatabase(){
        return $this->usersDatabase;
    }        

    function __construct() {    
        
        $host = $_ENV['DB_HOST'];
        $db   = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];
        $port = $_ENV['DB_PORT'];

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4"; 
        
        try {
            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->usersDatabase = new UserDatabase($this->pdo);
        } catch (PDOException $e) {
            die("Anslutning misslyckades: " . $e->getMessage());
        }
    }

    // Hämtar alla produkter från tabellen Shopping.Inventory
    function getAllProducts($sortCol = "id", $sortOrder = "asc"){
        $validCols = ["id", "name", "size", "price"];
        if(!in_array($sortCol, $validCols)){
            $sortCol = "id";
        }
        $sortOrder = ($sortOrder === "desc") ? "desc" : "asc";

        // Vi använder namnet 'Inventory' som syns i dina TS-filer
        $query = $this->pdo->query("SELECT * FROM Inventory ORDER BY $sortCol $sortOrder");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hämtar en specifik produkt baserat på ID
    function getProduct($id){
        $query = $this->pdo->prepare("SELECT * FROM Inventory WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Uppdaterar en produkt (matchar din updateProduct i Products.ts)
    function updateProduct($id, $name, $size, $img, $price) {
        $sql = "UPDATE Inventory SET name = ?, size = ?, img = ?, price = ? WHERE id = ?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$name, $size, $img, $price, $id]);
    }

    // Tar bort en produkt
    function deleteProduct($id){
        $query = $this->pdo->prepare("DELETE FROM Inventory WHERE id = ?");
        $query->execute([$id]);
    }
}
?>