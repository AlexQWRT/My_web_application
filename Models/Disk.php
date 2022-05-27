<?php
    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    }, true, false);

    class Disk extends Product {
        private $size;

        function __construct(
            $sku,
            $name,
            $price,
            $category,
            $size
        ) {
            parent::__construct($sku, $name, $price, $category);
            $this->size = $size;
        }

        function __toString() {
            return 'Disk{ ' . parent::__toString() . '; ' .  $this->size . ' MB }';
        }
    }    
?>