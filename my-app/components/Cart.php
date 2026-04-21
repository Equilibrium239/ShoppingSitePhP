<?php

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    echo "Du valde produkt med ID: " . $id;
}


if(isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    echo "Du lade till produkt ID " . $id . " i varukorgen!";
}
?>