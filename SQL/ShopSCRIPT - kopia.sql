CREATE DATABASE IF NOT EXISTS Shopping;
USE Shopping;


DROP TABLE IF EXISTS Inventory, Users, categories, admin;

CREATE TABLE categories (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    size VARCHAR(10) NOT NULL, -- Ökad till 10 för t.ex. "50cm"
    description VARCHAR(255) NOT NULL, -- Ökad för längre texter
    imageUrl VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    likes INT DEFAULT 0,
    category_id INT -- Vi skapar kolumnen direkt här istället för ALTER TABLE
);




-- 1. Lägg in Kategorier först
INSERT INTO categories (id, category_name) VALUES
(1, "Denim"),
(2, "Jacket"),
(3, "Hoodie"),
(4, "Footwear"),
(5, "T-shirt"),
(6, "Accessory"); 


INSERT INTO Inventory (id, name, size, description, imageUrl, price, likes, category_id) VALUES
(1, "Levis", "S", "Stylish jeans from Levis", "Levis Jeans.jpg", 100.00, 5, 1),
(2, "Napapijri", "XL", "Stylish jacket from Napapijiri", "Napapijri Jacket.jpg", 300.00, 31, 2),
(3, "Obey", "M", "Simple stylish look for an everyday use from Obey", "Obey Hoodie.jpg", 80.00, 84, 3),
(4, "Nike Air Force", "43", "An everyday shoe from Nike", "Nike Air Force.jpg", 150.00, 100, 4),
(5, "Supreme", "L", "A simple looking t-shirt from Supreme", "Supreme T-shirt.jpg", 120.00, 50, 5),
(6, "Replay", "L", "Quality Italian denim brand", "Replay Jean.jpg", 200.00, 90, 1),
(7, "Tiffany and Co", "50cm", "A gold bracelet for a fresh stylish look", "Bracelet.jpg", 800.00, 87, 6),
(8, "Adidas Samba", "42", "Classic football-inspired street shoe", "Samba.jpg", 110.00, 150, 4),
(9, "Carhartt Beanie", "oneS", "Warm acrylic watch hat in brown", "Beanie.jpg", 25.00, 45, 6),
(10, "Svart T-shirt", "L", "En klassisk svart t-shirt för vardagar", "Svart T-shirt.jpg", 15.00, 14, 5),
(11, "Zara Jeans", "M", "Slim fit denim jeans from Zara", "ZaraJeans.jpg", 79.99, 34, 1),
(12, "Diesel Denim", "L", "Premium blue denim from Diesel", "DieselDenim.jpg", 189.99, 67, 1),
(13, "North Face Jacket", "XL", "Warm winter jacket from The North Face", "NorthFaceJacket.jpg", 349.99, 120, 2),
(14, "Moncler Jacket", "M", "Luxury padded jacket from Moncler", "MonclerJacket.jpg", 899.99, 210, 2),
(15, "Champion Hoodie", "L", "Comfortable everyday hoodie", "ChampionHoodie.jpg", 69.99, 76, 3),
(16, "Essentials Hoodie", "XL", "Oversized Fear of God Essentials hoodie", "EssentialsHoodie.jpg", 129.99, 155, 3),
(17, "Nike Dunk", "42", "Classic Nike Dunk sneakers", "NikeDunk.jpg", 159.99, 180, 4),
(18, "Jordan 4 Retro", "44", "Popular Air Jordan 4 Retro shoes", "Jordan4.jpg", 249.99, 240, 4),
(19, "Palm Angels T-shirt", "L", "Streetwear t-shirt from Palm Angels", "PalmAngelsTshirt.jpg", 149.99, 89, 5),
(20, "Nike T-shirt", "M", "Simple sporty Nike t-shirt", "NikeTshirt.jpg", 39.99, 45, 5),
(21, "Gucci Belt", "oneS", "Luxury leather belt from Gucci", "GucciBelt.jpg", 499.99, 133, 6),
(22, "RayBan Sunglasses", "oneS", "Classic RayBan sunglasses", "RayBan.jpg", 199.99, 98, 6),
(23, "Levis 501", "L", "Original straight fit jeans from Levis", "Levis501.jpg", 109.99, 58, 1),
(24, "Puffer Jacket", "L", "Stylish black puffer jacket", "PufferJacket.jpg", 159.99, 87, 2),
(25, "Adidas Hoodie", "S", "Soft hoodie with Adidas logo", "AdidasHoodie.jpg", 74.99, 60, 3),
(26, "New Balance 550", "43", "Retro inspired New Balance sneakers", "NB550.jpg", 139.99, 172, 4),
(27, "Stussy T-shirt", "XL", "Classic Stussy streetwear t-shirt", "StussyTshirt.jpg", 59.99, 95, 5),
(28, "Prada Cap", "oneS", "Minimalistic luxury cap from Prada", "PradaCap.jpg", 299.99, 65, 6),
(29, "Tommy Hilfiger Jeans", "M", "Blue regular fit Tommy jeans", "TommyJeans.jpg", 119.99, 55, 1),
(30, "Varsity Jacket", "L", "American style varsity jacket", "VarsityJacket.jpg", 179.99, 110, 2);

USE Shopping;


SELECT id, name, category_id FROM Inventory;


UPDATE Inventory SET category_id = 1 WHERE id = 1; -- Levis -> Denim
UPDATE Inventory SET category_id = 2 WHERE id = 2; -- Napapijri -> Jacket
UPDATE Inventory SET category_id = 3 WHERE id = 3; -- Obey -> Hoodie
UPDATE Inventory SET category_id = 4 WHERE id = 4; -- Nike -> Footwear
UPDATE Inventory SET category_id = 5 WHERE id = 5; -- Supreme -> T-shirt
UPDATE Inventory SET category_id = 1 WHERE id = 6; -- Replay -> Denim
UPDATE Inventory SET category_id = 7 WHERE id = 7; -- Tiffany -> Accesory
UPDATE Inventory SET category_id = 4 WHERE id = 8; -- Adidas -> Footwear
UPDATE Inventory SET category_id = 7 WHERE id = 9; -- Carhartt -> Accessory
UPDATE Inventory SET category_id = 5 WHERE id = 10; -- Svart T-shirt



SELECT * FROM Inventory;
SELECT * FROM categories;
SELECT i.*, c.category_name 
FROM Inventory i
LEFT JOIN categories c ON i.category_id = c.id