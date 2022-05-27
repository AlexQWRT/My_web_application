<?php
    class Product {
        private $sku;      // int
        private $name;     // string
        private $price;    // float
        private $category; // int

        function __construct(
            $sku,
            $name,
            $price,
            $category
        ) {
            $this->sku = $sku;
            $this->name = $name;
            $this->price = $price;
            $this->category = $category;
        }

        function __toString() {
            return 'Product{ sku: ' . $this->sku . '; name: ' . $this->name . '; price: ' . $this->price . '$; category: ' . $this->category . ' }';
        }
    }
?>