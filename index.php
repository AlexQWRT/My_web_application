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
    <style>
        h1 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 3vw;
            margin-top: 0;
            margin-right: 0;
            margin-bottom: 0;
            margin-left: 0.5vw;
            padding: 0;
        }

        hr {
            margin: 0;
            width: 98%;
            left: 1%;
            border: 0.2vw solid #000000;
            position: relative;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 1vw;
            padding: 0;
            margin: 0;
        }

        input {
            border: 0.2vw solid #000000;
            border-radius: 0.7vw;
            display: inline-block;
            padding: 0.1vw 0.5vw; 
            text-decoration: none; 
            color: #000000;
            background-color: #ffffff;
            position: absolute;
            font-size: 2vw;
            cursor: pointer;
        }

        input.add {
            top: 0.3vw;
            right: 14.5vw;
        }

        input.mass_delete {
            top: 0.3vw;
            right: 0.5vw;
        }

        input:hover {
            box-shadow: 0 0 0.1vw rgba(0,0,0,0.3);
            background: #dedcdc;
        }

        input.delete-checkbox {
            position: relative;
            left: -8.5vw;
            width: 1vw;
            margin: 0;
        }

        table {
            margin: 1%;
            width: 98%;
        }

        td {
            border: 0.2vw solid #000000;
            border-radius: 0.8vw;
            padding: 0.5vw;
            text-align: center;
            width: 20%
        }
    </style>
</head>
<body>
    <h1>Product list</h1>
    <form action="add-product.php" method="post">
        <input type="submit" class="add" value="ADD">
    </form>
    <form action="index.php" method="POST">
        <input name="delete" type="submit" class="mass_delete" value="MASS DELETE">
        <hr color='black'>
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
                echo '<input type="checkbox" class="delete-checkbox" name="product' . $counter . '" value="' . $array['sku'] . '"><br>';
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
            echo '<input type="hidden" name="count" value="' . $counter . '">';

            mysqli_close($connection);

            spl_autoload_register(function ($class_name) {
                include 'Models\\' . $class_name . '.php';
            }, true, false);

        ?>
    </form>
</body>
</html>

