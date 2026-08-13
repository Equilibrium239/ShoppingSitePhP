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
        if (!$this->usersDatabase) {
            $this->usersDatabase = new UserDatabase($this->pdo);
        }
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
        } catch (PDOException $e) {
            die("Anslutning misslyckades: " . $e->getMessage());
        }
    }


    function addUserDetails($id, $streetaddress, $name, $postalCode, $city){
        $query = $this->pdo->prepare("INSERT INTO UserDetails (id, streetaddress, name, postalCode, city) VALUES (:id, :streetaddress, :name, :postalCode, :city)");
        $query->execute([
            "id" => $id,
            "streetaddress" => $streetaddress, 
            "name" => $name,
            "postalCode" => $postalCode,
            "city" => $city
        ]);
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

    // Hämtar produkter baserat på kategori och sortering
    function getProductsFilterd($category = null, $sort = 'default') {
          $sql = "SELECT Inventory.*, categories.category_name 
            FROM Inventory 
            LEFT JOIN categories ON Inventory.category_id = categories.id";
    $params = [];

    if ($category) {
        $sql .= " WHERE categories.category_name = :category";
        $params['category'] = $category;
    }

    switch ($sort) {
        case 'price_low':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price_high':
            $sql .= " ORDER BY price DESC";
            break;
        case 'name':
            $sql .= " ORDER BY Inventory.name ASC";
            break;
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Hämtar de mest populära produkterna baserat på antal likes
    function getPopularProducts($limit = 10) {
        $query = $this->pdo->prepare("SELECT * FROM Inventory ORDER BY likes DESC LIMIT :limit");
        $query->bindValue(':limit',(int)$limit, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function searchProducts($query) {

    $sql = "SELECT i.*, c.category_name 
            FROM Inventory i 
            LEFT JOIN categories c ON i.category_id = c.id 
            WHERE i.name LIKE :query 
            OR i.description LIKE :query 
            OR c.category_name LIKE :query";

    $stmt = $this->pdo->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->execute(['query' => $searchTerm]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    //hämtar admin baserat på användarnamn
    public function getAdmin($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    function getAllFreightRules(){
        $query = $this->pdo->query("SELECT id, zone_code as zoneCode, zone_name as zoneName, base_fee as baseFee, weight_modifier as weightMultiplier, free_shipping_threshold as freeShippingThreshold FROM freight_rules");
        $freightRules = $query->fetchAll(PDO::FETCH_CLASS, "FreightRule"); // KLASSNAMNET!!!
        return $freightRules;
    }

    function updateFreightRule($zoneCode, $zoneName, $baseFee, $weightMultiplier, $freeShippingLimit){
        //    
        $query = $this->pdo->prepare("INSERT INTO freight_rules (zone_code, zone_name, base_fee, weight_modifier," .
            " free_shipping_threshold) VALUES (:zoneCode, :zoneName, :baseFee, :weight_modifier, :free_shipping_threshold)" . 
            " ON DUPLICATE KEY UPDATE zone_name=:zoneName, base_fee=:baseFee, weight_modifier=:weight_modifier, free_shipping_threshold=:free_shipping_threshold");
        $query->execute([
            'zoneCode' => $zoneCode,
            'zoneName' => $zoneName,
            'baseFee' => $baseFee,
            'weight_modifier' => $weightMultiplier,
            'free_shipping_threshold' => $freeShippingLimit
        ]);
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

     public function getCartItems($userId, $session_id) {
    $cartItems = [];

    if ($userId) {
        $stmt = $this->pdo->prepare("
            SELECT ci.*, i.name AS productName, i.price AS productPrice,
                   (i.price * ci.quantity) AS rowPrice, i.imageUrl
            FROM CartItem ci
            JOIN Inventory i ON ci.productId = i.id
            WHERE ci.userId = ?
        ");
        $stmt->execute([$userId]);
    } else {
        $stmt = $this->pdo->prepare("
            SELECT ci.*, i.name AS productName, i.price AS productPrice,
                   (i.price * ci.quantity) AS rowPrice, i.imageUrl
            FROM CartItem ci
            JOIN Inventory i ON ci.productId = i.id
            WHERE ci.sessionId = ?
        ");
        $stmt->execute([$session_id]);
    }

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $item = new CartItem();
        $item->id = $row['id'];
        $item->productId = $row['productId'];
        $item->quantity = $row['quantity'];
        $item->productName = $row['productName'];
        $item->productPrice = $row['productPrice'];
        $item->rowPrice = $row['rowPrice'];
        $item->imageUrl = $row['imageUrl'];
        $cartItems[] = $item;
    }

    return $cartItems;
}

public function updateCartItem($userId, $session_id, $productId, $quantity) {
    if ($quantity <= 0) {
        $stmt = $this->pdo->prepare("DELETE FROM CartItem WHERE productId = ? AND (userId = ? OR sessionId = ?)");
        $stmt->execute([$productId, $userId, $session_id]);
    } else {
        $stmt = $this->pdo->prepare("
            INSERT INTO CartItem (productId, userId, sessionId, quantity)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = ?
        ");
        $stmt->execute([$productId, $userId, $session_id, $quantity, $quantity]);
    }
}

public function convertSessionToUser($session_id, $userId, $newSessionId) {
    $stmt = $this->pdo->prepare("UPDATE CartItem SET userId = ?, sessionId = ? WHERE sessionId = ?");
    $stmt->execute([$userId, $newSessionId, $session_id]);
}

public function clearCartItems($userId, $session_id) {
    if ($userId) {
        $stmt = $this->pdo->prepare("DELETE FROM CartItem WHERE userId = ?");
        $stmt->execute([$userId]);
    } else {
        $stmt = $this->pdo->prepare("DELETE FROM CartItem WHERE sessionId = ?");
        $stmt->execute([$session_id]);
    }
}

}