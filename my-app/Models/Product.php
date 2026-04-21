<?php 

class Product {
    public $id;
    public $name;
    public $description;
    public $size;
    public $price;
    public $imageUrl;

    public function __construct($id, $name, $description, $size, $price, $imageUrl) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->size = $size;
        $this->price = $price;
        $this->imageUrl = $imageUrl;
    }
}

?>