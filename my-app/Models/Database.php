<?php 
require_once(__DIR__ . '/Product.php');
require_once(__DIR__ . '/../Models/Database.php');
require_once(__DIR__ . '/../Models/UserDatabase.php');
require_once __DIR__ . '/../../vendor/autoload.php';
require_once(__DIR__ . '/../Models/Category.php');

class Database {
    public $pdo;
    private $usersDatabase;

    function getUsersDatabase(){
        return $this->usersDatabase;
    }        

    function __construct() {    
    
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        
        $host = "localhost";
        $db   = "Shopping";
        $user = "root";
        $pass = "root";
        $port = "3306";

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4"; 
        
        try {
            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //$this->usersDatabase = new UserDatabase($this->pdo);
        } catch (PDOException $e) {
            die("Anslutning misslyckades: " . $e->getMessage());
        }
    }

    // Hämtar alla produkter 
    function getAllProducts($sortCol = "id", $sortOrder = "asc"){
        $validCols = ["id", "name", "size", "price"];
        if(!in_array($sortCol, $validCols)){
            $sortCol = "id";
        }
        $sortOrder = ($sortOrder === "desc") ? "desc" : "asc";

        
        $query = $this->pdo->query("SELECT Inventory.*, categories.category_name FROM Inventory JOIN categories ON Inventory.category_id = categories.id ORDER BY $sortCol $sortOrder");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // specifik produkt baserat på ID
    function getProduct($id){
        $query = $this->pdo->prepare("SELECT * FROM Inventory WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Hämtar alla kategorier
    function getAllCategories(){
        $query = $this->pdo->query("SELECT * FROM categories");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hämtar produkter baserat på kategori id
    function getProductByCategory($categoryid) {
        $query = $this->pdo->prepare("SELECT * FROM Inventory WHERE category_id = :catId");
        $query->execute(["catId" => $categoryid]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hämtar de mest populära produkterna baserat på antal likes
    function getPopularProducts($limit = 4) {
        $query = $this->pdo->prepare("SELECT * FROM Inventory ORDER BY likes DESC LIMIT :limit");
        $query->bindValue(':limit',(int)$limit, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    //hämtar admin baserat på användarnamn
    public function getAdmin($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Uppdaterar en produkt
    function updateProduct($id, $name, $size, $description, $imageUrl, $price, $category_id) {
        $sql = "UPDATE Inventory SET name = ?, size = ?, img = ?, price = ? WHERE id = ?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$name, $size, $description, $imageUrl, $price, $category_id]);
    }

    public function addProduct($name, $size, $description, $imageUrl, $price, $category_id) {
        $sql = "INSERT INTO Inventory (name, size, description, imageUrl, price, category_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $size, $description, $imageUrl, $price, $category_id]);
    }

    // Tar bort en produkt
    function deleteProduct($id){
        $query = $this->pdo->prepare("DELETE FROM Inventory WHERE id = ?");
        $query->execute([$id]);
    }
}
?>