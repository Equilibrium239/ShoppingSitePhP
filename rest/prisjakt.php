<?php 
require_once(__DIR__ . '/../my-app/Models/Database.php');
require_once __DIR__ . '/../../vendor/autoload.php';
require_once(__DIR__ . '/../my-app/Models/userDatabase.php');



$db = new Database();

$allProducts = $db->getAllProducts();

foreach ($allProducts as $product) {
    ?>
    <h1><?php echo $product->name; ?></h1>
    <p><?php echo $product->description; ?></p>
    <p>Pris: <?php echo $product->price; ?> kr</p>
    <?php
}
?>



<rss xmlns:pj="https://schema.prisjakt.nu/ns/1.0" xmlns:g="http://base.google.com/ns/1.0" version="3.0">
  <channel>
    <title>Prisjakt Minimal Example Feed</title>
    <description>This is an example feed with the minimal values required</description>
    <link>https://schema.prisjakt.nu</link>
<?php 
foreach ($allProducts as $product) {
?>
    <item>
      <g:id><?php echo $product->id; ?></g:id>
      <g:title><?php echo $product->name; ?></g:title>
      <g:price><?php echo $product->price; ?> USD</g:price>
      <g:link>http://localhost:8080/product?pid=<?php echo $product->id; ?></g:link>
    </item>
<?php } ?>

  </channel>
</rss>
