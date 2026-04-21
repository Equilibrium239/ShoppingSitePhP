<?php 


$id = $_GET["id"];


echo "Du klickade på Product id: " . $id;


$database = new Database();


?>


<h1>Du har tryckt på <?php echo $id; ?>:</h1>
