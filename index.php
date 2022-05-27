<?php
    //connecting to MySQL
    $host = 'sql310.epizy.com';
    $user = 'epiz_31815620';
    $password = 'y8j2vgsp';
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
    <div id='header'>
        <h1>Product list</h1><input type="button" value="ADD"><input type="button" value="MASS DELETE" > 
    </div>    
    <hr color='black'>
<?php  
    if( !$connection ) {
        echo 'Failed to connect to database!<br>';
        echo mysqli_connect_error();
        exit();
    }

    if( !($result = mysqli_query($connection, 'SELECT * FROM `product`;')) ) {
        echo 'Product list is empty!';
        exit();
    }
    while( $array = mysqli_fetch_assoc($result) ) {
        echo '<input type="checkbox" class="delete-checkbox"><br>';
        echo 'SKU: ' . $array['sku'] . '<br>';
        echo $array['name'] . '<br>';
        echo $array['price'] . '$<br>';
        $properties_result = mysqli_query($connection, 'SELECT * FROM `properties_values` WHERE `sku`="' . $array['sku'] . '";');
        $properties_array = mysqli_fetch_assoc($properties_result);
        if( $array['category_id'] == 1 ) {
            echo 'Size: ' . $properties_array['property_value'] . 'MB<br>';
        }
        if( $array['category_id'] == 2 ) {
            echo 'Weight: ' . $properties_array['property_value'] . 'KG<br>';
        }
        if( $array['category_id'] == 3 ) {

            echo 'Size: ' . $properties_array['property_value'] . 'MB<br>';
        }
        echo '<hr>';
    }

    mysqli_close($connection);

    spl_autoload_register(function ($class_name) {
        include 'Models\\' . $class_name . '.php';
    }, true, false);

?>
</body>
</html>

