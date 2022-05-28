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
    <title>Product Add</title>
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

        input.text {
            width: 100%;
            height: 100%;
        }

        input.cancel {
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
            top: 0.3vw;
            right: 0.5vw;
        }

        input.save {
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
            top: 0.3vw;
            right: 9.1vw;
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
            margin-top: 1vw;
            margin-left: 1vw;
        }

        select {
            width: 100%;
        }

    </style>
</head>
<body>
    <h1>Product Add</h1>
    <form action="index.php" method="post">
        <input type="submit" class="cancel" value="CANCEL">
    </form>
    <form action="add-product.php" method="POST" id="product_form">
        <input name="save" type="submit" class="save" value="SAVE">
        <hr color='black'>
        <?php  
            if( !$connection ) {
                echo 'Failed to connect to database!<br>';
                echo mysqli_connect_error();
                exit();
            }
        ?>

        <table>
            <tr>
                <td>SKU:</td>
                <td><input type="text" name="sku" required></td>
            </tr>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name" required></td>
            </tr>
            <tr>
                <td>Price ($):</td>
                <td><input type="text" name="price" required></td>
            </tr>
            <tr>
                <td>Type switcher:</td>
                <td>
                    <select name="category" id="productType" required onchange="">
                        <option value="0" selected></option>
                        <?php
                            $result = mysqli_query($connection, 'SELECT * FROM `categories`;');
                            while( $array = mysqli_fetch_assoc($result) ) {
                                echo '<option value="' . $array['category_id'] . '" id="' . $array['category_name'] . '">' . $array['category_name'] . '</option>';
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <span id=""><tr>
                <td>Size (MB):</td>
                <td><input type="text" name="size" required></td>
            </tr>
            <tr>
                <td>Weight (KG):</td>
                <td><input type="text" name="weight" required></td>
            </tr>
            <tr>
                <td>Height (CM):</td>
                <td><input type="text" name="heiht" required></td>
            </tr>
            <tr>
                <td>Width (CM):</td>
                <td><input type="text" name="width" required></td>
            </tr>
            <tr>
                <td>Length (CM):</td>
                <td><input type="text" name="length" required></td>
            </tr>
        </table>

        <?php
            if (isset($_POST['save'])) {
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
            mysqli_close($connection);
        ?>
    </form>
</body>
</html>

