<?php
require_once(__DIR__ . '/../Models/Database.php');
require_once __DIR__ . '/../../vendor/autoload.php';

// Tell the browser this is XML, not HTML
header('Content-Type: application/xml; charset=utf-8');

$db = new Database();
$allProducts = $db->getAllProducts();

// Start XML output — nothing must be printed before this line
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss xmlns:pj="https://schema.prisjakt.nu/ns/1.0" xmlns:g="http://base.google.com/ns/1.0" version="3.0">
  <channel>
    <title>Our Store - Product Feed</title>
    <description>All products available in our store</description>
    <link>http://localhost:8080</link>
<?php foreach ($allProducts as $product): ?>
    <item>
      <g:id><?php echo htmlspecialchars((string)$product['id'], ENT_XML1, 'UTF-8'); ?></g:id>
      <g:title><?php echo htmlspecialchars($product['name'], ENT_XML1, 'UTF-8'); ?></g:title>
      <g:description><?php echo htmlspecialchars($product['description'] ?? '', ENT_XML1, 'UTF-8'); ?></g:description>
      <g:price><?php echo htmlspecialchars(number_format((float)$product['price'], 2, '.', ''), ENT_XML1, 'UTF-8'); ?> SEK</g:price>
      <g:link>http://localhost:8080/my-app/page/Product.php?id=<?php echo (int)$product['id']; ?></g:link>
      <g:image_link>http://localhost:8080/my-app/image/<?php echo htmlspecialchars($product['imageUrl'] ?? '', ENT_XML1, 'UTF-8'); ?></g:image_link>
      <g:condition>new</g:condition>
      <g:availability>in stock</g:availability>
    </item>
<?php endforeach; ?>
  </channel>
</rss>
