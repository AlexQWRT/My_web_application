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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Product list</h1><a href="add-product.php" class="add">ADD</a><a href="#" class="mass_delete">MASS DELETE</a>   
    <hr color='black'>
<?php  
    if( !$connection ) {
        echo 'Failed to connect to database!<br>';
        echo mysqli_connect_error();
        exit();
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
        echo '<input type="checkbox" class="delete-checkbox"><br>';
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
        echo '</td>';
    }
    echo '</tr></table>';

    mysqli_close($connection);

    spl_autoload_register(function ($class_name) {
        include 'Models\\' . $class_name . '.php';
    }, true, false);

?>
</body>
</html>

