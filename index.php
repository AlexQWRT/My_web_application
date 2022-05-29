<?php
/*
    $host = 'sql310.epizy.com';
    $user = 'epiz_31815620';
    $password = 'y8j2vgsp';
    $database = 'epiz_31815620_shop';*/

    //connecting to MySQL
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'epiz_31815620_shop';
    $connection = mysqli_connect($host, $user, $password, $database);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet"> 
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
            padding: 0;
            margin: 0;
            color: #6e5227;
            font-size: 1vw;
            background-color: #fff3e0;
        }
        div.body {
            width: 80%;
            min-height: 100vh;
            margin-left: 10%;
            margin-right: 10%;
            background-color: #f5d99a;

        }
        span.head {
            font-size: 3vw;
            margin-left: 1vw;
        }
        button {
            font-family: 'Ubuntu', sans-serif;
            height: 2.4vw;
            border: 0.15vw solid #6e5227;
            border-radius: 0.7vw;
            display: inline-block;
            padding: 0.1vw 0.5vw; 
            text-decoration: none; 
            color: #6e5227;
            background-color: #fff3e0;
            font-size: 1.8vw;
            cursor: pointer;
        }
        div.buttons {
            position: absolute;
            right: 11%;
            top: 0.7vw
        }
        hr {
            width: 96%;
            margin-left: 2%;
            margin-right: 2%;
            margin-top: 0.3vw;
            margin-bottom: 0.7vw;
            color: #6e5227;
        }
        table {
            width:98%;
            margin-left: 1%;
            margin-right: 1%;
            margin-bottom: 0.5vw;
        }
        td {
            width: 20%;
            background-color: #fff3e0;
            border: 0.15vw solid #6e5227;
            border-radius: 0.7vw;
        }
        div.table_text {
            text-align: center;
        }
        input.delete-checkbox {
            width: 1vw;
        }
    </style>
</head>
<body>
    <div class="body">
        <span class="head">Product list</span>
        <div class="buttons">
            <a href="add-product.php"><button class="add">ADD</button></a>
            <button form="products" type="submit" name="delete" class="mass_delete">MASS DELETE</button>
        </div>
        <hr>
        <form action="index.php" method="POST" id="products">
            <?php  
                if( !$connection ) {
                    echo 'Failed to connect to database!<br>';
                    echo mysqli_connect_error();
                    exit();
                }
                
                if (isset($_POST['delete'])) {
                    for( $i = 1; $i <= $_POST['count']; $i++ ) {
                        if (isset($_POST['product' . $i])) {
                            mysqli_query($connection, 'DELETE FROM products WHERE `products`.`sku` = "' . $_POST['product' . $i] . '"');
                        }
                    }
                }

                if (isset($_POST['sku'])) {
                    $categories_query = mysqli_query($connection, 'SELECT * FROM `properties` WHERE `category_id` = ' . $_POST['category'] . ';');

                    mysqli_query($connection, 'INSERT INTO `products` (`sku`, `name`, `price`, `category_id`) VALUES ("'
                    . $_POST['sku'] .'", "'
                    . $_POST['name'] . '", "'
                    . $_POST['price'] . '", "'
                    . $_POST['category'] . '");');
                    while( $categories = mysqli_fetch_assoc($categories_query) ) {
                        mysqli_query($connection, 'INSERT INTO `properties_values` (`property_id`, `property_value`, `sku`) VALUES ("'
                        . $categories['property_id'] . '", "'
                        . $_POST[$categories['property_name']] . '", "'
                        . $_POST['sku'] . '");');
                    }
                }

                if( !($result = mysqli_query($connection, 'SELECT * FROM `products` INNER JOIN `categories` ON (products.category_id = categories.category_id);')) ) {
                    echo 'Product list is empty!';
                    exit();
                }

                $counter = 0;
                $products_per_line = 5;
                echo '<table>';
                while( $array = mysqli_fetch_assoc($result) ) {
                    if( $counter % $products_per_line == 0 ) {
                        echo '</tr><tr>';
                    }
                    $counter++;
                    echo '<td>';
                    echo '<input type="checkbox" class="delete-checkbox" name="product' . $counter . '" value="' . $array['sku'] . '"><br><div class="table_text">';
                    echo 'SKU: ' . $array['sku'] . '<br>';
                    echo $array['name'] . '<br>';
                    echo $array['price'] . '$<br>';
                    $properties_result = mysqli_query($connection, 'SELECT * FROM `properties_values` WHERE `sku`="' . $array['sku'] . '";');
                    $properties_array = mysqli_fetch_assoc($properties_result);
                    if( $array['category_name'] == 'DVD' ) {
                        echo 'Size: ' . $properties_array['property_value'] . 'MB<br>';
                    }
                    if( $array['category_name'] == 'Book' ) {
                        echo 'Weight: ' . $properties_array['property_value'] . 'KG<br>';
                    }
                    if( $array['category_name'] == 'Furniture' ) {
                        $height = $properties_array['property_value'];
                        $properties_array = mysqli_fetch_assoc($properties_result);
                        $width = $properties_array['property_value'];
                        $properties_array = mysqli_fetch_assoc($properties_result);
                        $length = $properties_array['property_value'];
                        echo 'Dismensions: ' . $height . 'x' . $width . 'x' . $length . '<br>';
                    }
                    echo '</div></td>';
                }
                echo '</tr></table>';
                echo '<input type="hidden" name="count" value="' . $counter . '">';

                mysqli_close($connection);

                spl_autoload_register(function ($class_name) {
                    include 'Models\\' . $class_name . '.php';
                }, true, false);

            ?>
        </form>
    </div>
</body>
</html>

